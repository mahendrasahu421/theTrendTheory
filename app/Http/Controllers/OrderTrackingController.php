<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\RateLimiter;

class OrderTrackingController extends Controller
{
    /**
     * Display the order tracking landing page or tracking results if order number is supplied.
     */
    public function index(Request $request)
    {
        $orderNumber = trim($request->query('order') ?: $request->query('order_number') ?: '');
        $phone = trim($request->query('phone') ?: '');

        $order = null;
        $timeline = null;
        $courierUrl = null;

        if ($orderNumber) {
            $order = $this->findOrder($orderNumber, $phone);
            if ($order) {
                $timeline = $this->buildTrackingTimeline($order);
                $courierUrl = $this->getCourierTrackingUrl($order->courier_name, $order->tracking_number);
            }
        }

        // All orders for logged-in user with pagination
        $userOrders = collect();
        if (auth()->check()) {
            $userOrders = Order::with(['items.product'])
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate(5)
                ->withQueryString();
        }

        return view('froentend.tracking.index', compact('order', 'timeline', 'courierUrl', 'orderNumber', 'phone', 'userOrders'));
    }

    /**
     * Direct tracking page for a specific order number.
     */
    public function track(Request $request, string $orderNumber)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('order_number', $orderNumber)
            ->orWhere('id', $orderNumber)
            ->first();

        if (!$order) {
            return redirect()->route('order.track')
                ->with('error', "Order #{$orderNumber} was not found. Please verify your Order ID.");
        }

        $timeline = $this->buildTrackingTimeline($order);
        $courierUrl = $this->getCourierTrackingUrl($order->courier_name, $order->tracking_number);

        $userOrders = collect();
        if (auth()->check()) {
            $userOrders = Order::with(['items'])
                ->where('user_id', auth()->id())
                ->where('id', '!=', $order->id)
                ->latest()
                ->take(4)
                ->get();
        }

        return view('froentend.tracking.index', [
            'order'       => $order,
            'timeline'    => $timeline,
            'courierUrl'  => $courierUrl,
            'orderNumber' => $order->order_number,
            'phone'       => $order->shipping_phone,
            'userOrders'  => $userOrders,
        ]);
    }

    /**
     * Handle form submission to search and redirect to tracking.
     */
    public function search(Request $request)
    {
        $throttleKey = 'order_track_search|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 30)) {
            return back()->with('error', 'Too many tracking requests. Please wait a minute and try again.');
        }
        RateLimiter::hit($throttleKey, 60);

        $request->validate([
            'order_number' => 'required|string|max:100',
            'phone'        => 'nullable|string|max:30',
        ]);

        $orderNumber = trim($request->input('order_number'));
        $phone = trim($request->input('phone') ?: '');

        $order = $this->findOrder($orderNumber, $phone);

        if (!$order) {
            return back()->withInput()->with('error', "Could not find any order matching '{$orderNumber}'. Please check the order number sent to your email or SMS.");
        }

        return redirect()->route('order.track.detail', $order->order_number);
    }

    /**
     * API endpoint for fetching live tracking data.
     */
    public function apiTrack(Request $request, string $orderNumber)
    {
        $throttleKey = 'order_track_api|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 45)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many tracking requests. Please wait a moment.',
            ], 429);
        }
        RateLimiter::hit($throttleKey, 60);

        $order = Order::with(['items.product', 'user'])
            ->where('order_number', $orderNumber)
            ->orWhere('id', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => "Order #{$orderNumber} not found.",
            ], 404);
        }

        $timeline = $this->buildTrackingTimeline($order);
        $courierUrl = $this->getCourierTrackingUrl($order->courier_name, $order->tracking_number);

        return response()->json([
            'success'      => true,
            'order_number' => $order->order_number,
            'status'       => $order->status,
            'timeline'     => $timeline,
            'courier'      => [
                'name'            => $order->courier_name,
                'tracking_number' => $order->tracking_number,
                'tracking_url'    => $courierUrl,
            ],
            'shipping'     => [
                'name'    => $order->shipping_name,
                'city'    => $order->shipping_city,
                'state'   => $order->shipping_state,
                'pincode' => $order->shipping_pincode,
            ],
        ]);
    }

    /**
     * Find order by number or ID, with optional phone check.
     */
    protected function findOrder(string $orderNumber, string $phone = ''): ?Order
    {
        $query = Order::with(['items.product', 'user'])
            ->where(function ($q) use ($orderNumber) {
                $q->where('order_number', $orderNumber)
                  ->orWhere('id', $orderNumber)
                  ->orWhere('tracking_number', $orderNumber);
            });

        if (!empty($phone)) {
            $cleanedPhone = preg_replace('/[^0-9]/', '', $phone);
            $last10 = substr($cleanedPhone, -10);
            if (strlen($last10) === 10) {
                $query->where(function ($q) use ($last10) {
                    $q->where('shipping_phone', 'like', "%{$last10}%")
                      ->orWhereHas('user', fn ($uq) => $uq->where('phone', 'like', "%{$last10}%"));
                });
            }
        }

        return $query->first();
    }

    /**
     * Construct a realistic, beautiful multi-step timeline.
     */
    protected function buildTrackingTimeline(Order $order): array
    {
        $status = strtolower($order->status ?: 'pending');
        $createdAt = $order->created_at ?: now();

        $isCancelled = in_array($status, ['cancelled', 'canceled']);
        $isRefunded = in_array($status, ['refunded', 'returned']);

        // Step completion flags
        $stepConfirmed = in_array($status, ['confirmed', 'processing', 'shipped', 'delivered']);
        $stepProcessing = in_array($status, ['processing', 'shipped', 'delivered']);
        $stepShipped = in_array($status, ['shipped', 'delivered']);
        $stepOutForDelivery = $status === 'delivered' || ($status === 'shipped' && $createdAt->diffInDays(now()) >= 2);
        $stepDelivered = $status === 'delivered';

        // Calculation of progress percentage
        $progressPct = 20;
        if ($stepDelivered) {
            $progressPct = 100;
        } elseif ($stepOutForDelivery) {
            $progressPct = 85;
        } elseif ($stepShipped) {
            $progressPct = 65;
        } elseif ($stepProcessing) {
            $progressPct = 40;
        } elseif ($stepConfirmed) {
            $progressPct = 20;
        }

        if ($isCancelled || $isRefunded) {
            $progressPct = 100;
        }

        // Expected delivery date calculation (3 to 5 business days from order creation)
        $expectedDeliveryDate = $createdAt->copy()->addDays(4)->format('l, d M Y');

        $steps = [
            [
                'key'         => 'confirmed',
                'title'       => 'Order Placed & Confirmed',
                'description' => 'Your order has been placed and payment verified.',
                'date'        => $createdAt->format('d M Y, h:i A'),
                'is_done'     => $stepConfirmed,
                'is_current'  => $status === 'confirmed' || $status === 'pending',
                'icon'        => 'bi-receipt-cutoff',
            ],
            [
                'key'         => 'processing',
                'title'       => 'Quality Check & Packing',
                'description' => 'Items are packed and sealed in tamper-proof packaging at our fulfillment warehouse.',
                'date'        => $stepProcessing ? $createdAt->copy()->addHours(12)->format('d M Y, h:i A') : 'Estimated within 24 hours',
                'is_done'     => $stepProcessing,
                'is_current'  => $status === 'processing',
                'icon'        => 'bi-box-seam-fill',
            ],
            [
                'key'         => 'shipped',
                'title'       => 'Dispatched & In Transit',
                'description' => $order->courier_name
                    ? "Handed over to {$order->courier_name}" . ($order->tracking_number ? " (AWB: {$order->tracking_number})" : '')
                    : 'Dispatched via express surface logistics partner.',
                'date'        => $stepShipped ? $createdAt->copy()->addDay()->format('d M Y, h:i A') : 'Estimated ' . $createdAt->copy()->addDay()->format('d M Y'),
                'is_done'     => $stepShipped,
                'is_current'  => $status === 'shipped' && !$stepOutForDelivery,
                'icon'        => 'bi-truck',
            ],
            [
                'key'         => 'out_for_delivery',
                'title'       => 'Out for Delivery',
                'description' => 'Courier delivery executive is out for delivery to your shipping address.',
                'date'        => $stepOutForDelivery ? ($order->delivered_at ? Carbon::parse($order->delivered_at)->subHours(3)->format('d M Y, h:i A') : now()->format('d M Y, h:i A')) : 'Expected ' . $expectedDeliveryDate,
                'is_done'     => $stepOutForDelivery,
                'is_current'  => $stepOutForDelivery && !$stepDelivered,
                'icon'        => 'bi-bicycle',
            ],
            [
                'key'         => 'delivered',
                'title'       => 'Delivered',
                'description' => 'Package safely handed over to you.',
                'date'        => $order->delivered_at ? Carbon::parse($order->delivered_at)->format('d M Y, h:i A') : ($stepDelivered ? now()->format('d M Y, h:i A') : 'Expected by ' . $expectedDeliveryDate),
                'is_done'     => $stepDelivered,
                'is_current'  => $stepDelivered,
                'icon'        => 'bi-patch-check-fill',
            ],
        ];

        return [
            'status'                => $status,
            'status_label'          => $this->getStatusLabel($status),
            'status_color'          => $this->getStatusColor($status),
            'progress_pct'          => $progressPct,
            'is_cancelled'          => $isCancelled,
            'is_refunded'           => $isRefunded,
            'expected_delivery'     => $expectedDeliveryDate,
            'steps'                 => $steps,
        ];
    }

    protected function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending'    => 'Order Pending Confirmation',
            'confirmed'  => 'Order Confirmed',
            'processing' => 'Order Being Packed',
            'shipped'    => 'Shipped & In Transit',
            'delivered'  => 'Successfully Delivered',
            'cancelled'  => 'Order Cancelled',
            'refunded'   => 'Refund Processed',
            'returned'   => 'Order Returned',
            default      => ucfirst($status),
        };
    }

    protected function getStatusColor(string $status): string
    {
        return match ($status) {
            'confirmed'  => 'info',
            'processing' => 'warning',
            'shipped'    => 'primary',
            'delivered'  => 'success',
            'cancelled', 'refunded' => 'danger',
            default      => 'secondary',
        };
    }

    /**
     * Generate direct tracking URL for known Indian couriers.
     */
    protected function getCourierTrackingUrl(?string $courierName, ?string $trackingNumber): ?string
    {
        if (empty($trackingNumber)) {
            return null;
        }

        $courier = strtolower(trim($courierName ?: ''));
        $awb = urlencode(trim($trackingNumber));

        if (str_contains($courier, 'delhivery')) {
            return "https://www.delhivery.com/track/package/{$awb}";
        }
        if (str_contains($courier, 'bluedart') || str_contains($courier, 'blue dart')) {
            return "https://www.bluedart.com/tracking?track={$awb}";
        }
        if (str_contains($courier, 'dtdc')) {
            return "https://www.dtdc.in/tracking/shipment-tracking.asp?strCnNo={$awb}";
        }
        if (str_contains($courier, 'ekart')) {
            return "https://ekartlogistics.com/shipmenttrack/{$awb}";
        }
        if (str_contains($courier, 'xpressbees') || str_contains($courier, 'xpress')) {
            return "https://www.xpressbees.com/track?isawb=Yes&trackid={$awb}";
        }
        if (str_contains($courier, 'shadowfax')) {
            return "https://tracker.shadowfax.in/#/track?awb={$awb}";
        }
        if (str_contains($courier, 'shiprocket')) {
            return "https://shiprocket.co/tracking/{$awb}";
        }
        if (str_contains($courier, 'ecom')) {
            return "https://ecomexpress.in/tracking/?awb_field={$awb}";
        }
        if (str_contains($courier, 'post') || str_contains($courier, 'speed post')) {
            return "https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx";
        }

        return "https://www.google.com/search?q=" . urlencode(($courierName ?: 'courier') . " tracking " . $trackingNumber);
    }
}
