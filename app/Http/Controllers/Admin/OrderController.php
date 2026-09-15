<?php
// app/Http/Controllers/Admin/OrderController.php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
 
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $totalOrders = Order::count();

        $statusCounts = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $paymentCounts = Order::selectRaw('payment_status, COUNT(*) as total')
            ->groupBy('payment_status')
            ->pluck('total', 'payment_status');

        $codCount = Order::where(function($q) {
            $q->whereRaw("LOWER(payment_method) = 'cod'")->orWhereNull('payment_method');
        })->count();

        $prepaidCount = Order::where(function($q) {
            $q->whereRaw("LOWER(payment_method) != 'cod'")->whereNotNull('payment_method');
        })->count();

        return view('admin.orders.index', compact('totalOrders','statusCounts','paymentCounts','codCount','prepaidCount'));
    }

    public function ajax(Request $request)
    {
        $perPageInput = $request->get('per_page', 15);
        $perPage = $perPageInput === 'all' ? 5000 : max(1, min((int) $perPageInput, 500));
        $page = (int) $request->get('page', 1);
        $search = trim($request->get('search', ''));
        $status = $request->get('status', '');
        $method = $request->get('method', '');
        $payment = $request->get('payment', '');
        $sort = $request->get('sort', 'latest');

        $query = Order::with(['user', 'items.product']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($payment) {
            $query->where('payment_status', $payment);
        }

        if ($method === 'cod') {
            $query->where(function($q) {
                $q->whereRaw("LOWER(payment_method) = 'cod'")->orWhereNull('payment_method');
            });
        } elseif ($method === 'prepaid') {
            $query->where(function($q) {
                $q->whereRaw("LOWER(payment_method) != 'cod'")->whereNotNull('payment_method');
            });
        }

        if ($search) {
            $cleanSearch = $search;
            if (str_contains($search, '|')) {
                foreach (explode('|', $search) as $part) {
                    if (str_starts_with($part, 'ORDER:')) {
                        $cleanSearch = trim(substr($part, 6));
                        break;
                    } elseif (str_starts_with($part, 'AWB:')) {
                        $cleanSearch = trim(substr($part, 4));
                        break;
                    }
                }
            }

            $query->where(function ($q) use ($search, $cleanSearch) {
                $q->where('order_number', 'like', '%' . $cleanSearch . '%')
                  ->orWhere('tracking_number', 'like', '%' . $cleanSearch . '%')
                  ->orWhere('shipping_name', 'like', '%' . $search . '%')
                  ->orWhere('shipping_phone', 'like', '%' . $search . '%')
                  ->orWhere('shipping_city', 'like', '%' . $search . '%')
                  ->orWhere('shipping_state', 'like', '%' . $search . '%')
                  ->orWhereHas('items', function($iq) use ($search) {
                      $iq->where('product_name', 'like', '%' . $search . '%')
                         ->orWhere('sku', 'like', '%' . $search . '%');
                  });
            });
        }

        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'amount_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $statusCounts = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $codCount = Order::where(function($q) {
            $q->whereRaw("LOWER(payment_method) = 'cod'")->orWhereNull('payment_method');
        })->count();

        $prepaidCount = Order::where(function($q) {
            $q->whereRaw("LOWER(payment_method) != 'cod'")->whereNotNull('payment_method');
        })->count();

        $rows = collect($paginator->items())->map(function ($order) {
            $isCod = strtolower($order->payment_method ?? '') === 'cod' || empty($order->payment_method);
            $payMethod = strtoupper($order->payment_method ?: 'COD');

            $firstItem = $order->items->first();
            $itemImage = null;
            $itemName = $firstItem ? $firstItem->product_name : 'No items';
            $itemSku = $firstItem ? ($firstItem->sku ?: 'SKU-' . $firstItem->product_id) : '';
            $itemSize = $firstItem ? $firstItem->size : null;

            if ($firstItem) {
                $itemImage = $firstItem->product_image;
                if (!$itemImage && $firstItem->product) {
                    $itemImage = $firstItem->product->image;
                }
            }
            if (!$itemImage) {
                $itemImage = asset('assets/images/placeholder.png');
            }

            return [
                'id'               => $order->id,
                'order_number'     => $order->order_number,
                'shipping_name'    => $order->shipping_name ?: 'Guest Customer',
                'shipping_phone'   => $order->shipping_phone ?: '—',
                'shipping_city'    => $order->shipping_city ?: '',
                'shipping_state'   => $order->shipping_state ?: '',
                'items_count'      => $order->items->count(),
                'item_image'       => $itemImage,
                'item_name'        => $itemName,
                'item_sku'         => $itemSku,
                'item_size'        => $itemSize,
                'item_design_side' => $firstItem ? $firstItem->design_side : null,
                'total_amount'     => (float) $order->total_amount,
                'formatted_amount' => '₹' . number_format($order->total_amount),
                'payment_method'   => $payMethod,
                'is_cod'           => $isCod,
                'payment_status'   => $order->payment_status ?: 'pending',
                'status'           => $order->status ?: 'pending',
                'courier_name'     => $order->courier_name,
                'tracking_number'  => $order->tracking_number,
                'created_date'     => $order->created_at ? $order->created_at->format('d M Y') : '—',
                'created_time'     => $order->created_at ? $order->created_at->format('h:i A') : '—',
                'show_url'         => route('admin.orders.show', $order),
            ];
        });

        return response()->json([
            'success' => true,
            'orders'  => $rows,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem() ?? 0,
                'to'           => $paginator->lastItem() ?? 0,
            ],
            'kpis' => [
                'total'        => Order::count(),
                'pending'      => $statusCounts->get('pending', 0),
                'confirmed'    => $statusCounts->get('confirmed', 0),
                'processing'   => $statusCounts->get('processing', 0),
                'shipped'      => $statusCounts->get('shipped', 0),
                'delivered'    => $statusCounts->get('delivered', 0),
                'cancelled'    => $statusCounts->get('cancelled', 0),
                'cod'          => $codCount,
                'prepaid'      => $prepaidCount,
            ]
        ]);
    }
 
    public function quickStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'          => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'payment_status'  => 'nullable|in:pending,paid,failed,refunded',
            'courier_name'    => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $data = ['status' => $newStatus];
        if ($request->filled('payment_status')) {
            $data['payment_status'] = $request->payment_status;
        }
        if ($request->has('courier_name')) {
            $data['courier_name'] = $request->courier_name;
        }
        if ($request->has('tracking_number')) {
            $data['tracking_number'] = $request->tracking_number;
        }
        if ($newStatus === 'delivered') {
            $data['delivered_at'] = now();
        }

        $order->update($data);

        // Send notification to customer on order lifecycle action (Only if order status changed, NOT on payment change)
        if ($oldStatus !== $newStatus) {
            try {
                app(\App\Services\NotificationService::class)->notifyOrderStatus(
                    $order,
                    $newStatus,
                    $request->courier_name,
                    $request->tracking_number
                );
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Order #{$order->order_number} status updated to " . ucfirst($order->status) . ' and notification sent!',
            'order'   => [
                'id'             => $order->id,
                'status'         => $order->status,
                'payment_status' => $order->payment_status,
            ]
        ]);
    }

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'order_ids'       => 'required|array',
            'order_ids.*'     => 'integer|exists:orders,id',
            'status'          => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'payment_status'  => 'nullable|in:pending,paid,failed,refunded',
            'courier_name'    => 'nullable|string|max:100',
        ]);

        $newStatus = $request->status;
        $data = ['status' => $newStatus];
        if ($request->filled('payment_status')) {
            $data['payment_status'] = $request->payment_status;
        }
        if ($request->filled('courier_name')) {
            $data['courier_name'] = $request->courier_name;
        }
        if ($newStatus === 'delivered') {
            $data['delivered_at'] = now();
        }

        $orders = Order::whereIn('id', $request->order_ids)->get();
        $updatedCount = 0;

        foreach ($orders as $order) {
            $oldStatus = $order->status;
            $order->update($data);
            $updatedCount++;

            // Trigger customer notification for each order whose status was updated
            if ($oldStatus !== $newStatus) {
                try {
                    app(\App\Services\NotificationService::class)->notifyOrderStatus(
                        $order,
                        $newStatus,
                        $request->courier_name
                    );
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully updated {$updatedCount} orders to " . ucfirst($newStatus) . ' and notified customers!',
            'count'   => $updatedCount,
        ]);
    }

    public function bulkShippingLabels(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('ids', '')))
            ->map(fn ($id) => (int) trim($id))
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->take(200)
            ->values();

        if ($ids->isEmpty()) {
            return redirect()->route('admin.orders.index')->with('error', 'Please select at least one order to print labels.');
        }

        $orders = Order::with(['items.product', 'user'])
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn ($order) => $ids->search($order->id))
            ->values();

        if ($orders->isEmpty()) {
            abort(404, 'No matching orders found for label printing.');
        }

        return view('invoices.shipping_labels_bulk', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user','items');
        return view('admin.orders.show', compact('order'));
    }
 
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status'           => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'payment_status'   => 'required|in:pending,paid,failed,refunded',
            'tracking_number'  => 'nullable|string|max:100',
            'courier_name'     => 'nullable|string|max:100',
        ]);
 
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update($request->only('status','payment_status','tracking_number','courier_name'));
 
        if ($newStatus === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }
 
        // Trigger customer notification for order lifecycle status action
        if ($oldStatus !== $newStatus) {
            try {
                app(\App\Services\NotificationService::class)->notifyOrderStatus(
                    $order,
                    $newStatus,
                    $request->courier_name,
                    $request->tracking_number
                );
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return back()->with('success','Order updated and notification sent to customer!');
    }
 
    public function destroy(Order $order)
    {
        $order->update(['status' => 'cancelled']);
        return back()->with('success','Order cancelled.');
    }
}
 
