<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Product;
use App\Models\Refund;
use App\Models\UserRefundAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnController extends Controller
{
    // ── GET /my-returns ─────────────────────────────────────
    public function index()
    {
        $returns = OrderReturn::with(['order.items.product', 'refund'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('froentend.returns.index', compact('returns'));
    }

    // ── GET /orders/{order}/return ───────────────────────────
    public function create(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        // Must be delivered to return
        if (!in_array(strtolower($order->status), ['delivered', 'completed'])) {
            return redirect()->route('order.track')
                ->with('error', 'Only delivered orders can be returned or exchanged.');
        }

        // Already has a return/exchange request
        if ($order->return()->exists()) {
            return redirect()->route('order.return.show', $order->return->id)
                ->with('info', 'A return/exchange request already exists for this order.');
        }

        $order->load('items.product');

        [$exchangeSizes, $exchangeColors] = $this->exchangeOptionsForOrder($order);
        $defaultExchangeSize = old('exchange_size', $order->items->firstWhere('size')?->size ?: $exchangeSizes->first());
        $defaultExchangeColor = old(
            'exchange_color',
            $order->items->firstWhere('color')?->color
                ?: optional($order->items->first()?->product)->color_name
                ?: $exchangeColors->first()
        );

        $pincodeService = app(\App\Services\PincodeService::class);
        $pincodeCheck = $pincodeService->checkPincode((string)$order->shipping_pincode);
        $isExchangeOnly = (bool)($pincodeCheck['is_exchange_only'] ?? false);

        $order->loadMissing('payment');

        $originalPaymentAvailable = $this->canRefundToOriginalPayment($order);

        $refundAccounts = auth()->user()
            ->refundAccounts()
            ->orderByDesc('is_primary')
            ->latest()
            ->get();

        $primaryRefundAccount = $refundAccounts->firstWhere('is_primary', true);

        return view('froentend.returns.create', compact(
            'order',
            'isExchangeOnly',
            'pincodeCheck',
            'refundAccounts',
            'primaryRefundAccount',
            'originalPaymentAvailable',
            'exchangeSizes',
            'exchangeColors',
            'defaultExchangeSize',
            'defaultExchangeColor'
        ));
    }

    // ── POST /orders/{order}/return ──────────────────────────
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        if (!in_array(strtolower($order->status), ['delivered', 'completed'])) {
            return back()->with('error', 'Only delivered orders can be returned or exchanged.');
        }

        if ($order->return()->exists()) {
            return back()->with('error', 'A return/exchange request already exists for this order.');
        }

        // Check if destination pincode is exchange-only
        $pincodeService = app(\App\Services\PincodeService::class);
        $pincodeCheck = $pincodeService->checkPincode((string)$order->shipping_pincode);
        $isExchangeOnly = (bool)($pincodeCheck['is_exchange_only'] ?? false);

        if ($isExchangeOnly && $request->input('type') === 'return') {
            return back()->with('error', "Refund returns are not supported for delivery pincode {$order->shipping_pincode}. Only Size/Color replacement exchanges are permitted.")->withInput();
        }

        $order->loadMissing('items.product.variants.size', 'items.product.variants.color');
        [$exchangeSizes, $exchangeColors] = $this->exchangeOptionsForOrder($order);

        $validated = $request->validate([
            'type'             => 'required|in:return,exchange',
            'reason'           => 'required|string|max:80',
            'description'      => 'nullable|string|max:1000',
            'exchange_size'    => 'nullable|string|max:20',
            'exchange_color'   => 'nullable|string|max:40',
            // Refund details
            'refund_method'    => 'required|in:original_payment,bank_transfer,upi,store_credit',
            'upi_id'           => 'nullable|string|max:120',
            'bank_name'        => 'nullable|string|max:80',
            'account_number'   => 'nullable|string|max:40',
            'ifsc_code'        => 'nullable|string|max:20',
            'account_holder'   => 'nullable|string|max:120',
            'refund_account_id' => 'nullable|integer',
            'save_refund_account' => 'nullable|boolean',
            'make_primary_refund_account' => 'nullable|boolean',
        ]);

        if ($validated['type'] === 'exchange') {
            if (empty($validated['exchange_size']) || !$exchangeSizes->contains($validated['exchange_size'])) {
                return back()->withErrors(['exchange_size' => 'Please select a valid available exchange size.'])->withInput();
            }

            if ($exchangeColors->isNotEmpty() && (empty($validated['exchange_color']) || !$exchangeColors->contains($validated['exchange_color']))) {
                return back()->withErrors(['exchange_color' => 'Please select a valid available exchange color.'])->withInput();
            }
        }

        $selectedRefundAccount = null;
        if (!empty($validated['refund_account_id'])) {
            $selectedRefundAccount = UserRefundAccount::where('user_id', auth()->id())
                ->where('id', $validated['refund_account_id'])
                ->first();
        }

        if ($selectedRefundAccount && in_array($validated['refund_method'], ['bank_transfer', 'upi'], true)) {
            $validated['refund_method'] = $selectedRefundAccount->type;
            $validated['upi_id'] = $selectedRefundAccount->upi_id;
            $validated['bank_name'] = $selectedRefundAccount->bank_name;
            $validated['account_number'] = $selectedRefundAccount->account_number;
            $validated['ifsc_code'] = $selectedRefundAccount->ifsc_code;
            $validated['account_holder'] = $selectedRefundAccount->account_holder;
        }

        if ($validated['type'] === 'return' && $validated['refund_method'] === 'original_payment' && !$this->canRefundToOriginalPayment($order)) {
            return back()
                ->withErrors(['refund_method' => 'Original payment refund is not available for this order. Please add UPI or bank details.'])
                ->withInput(array_merge($request->all(), ['refund_method' => 'bank_transfer']));
        }

        // Validate bank/UPI fields based on method
        if (
            $validated['type'] === 'return'
            &&
            $validated['refund_method'] === 'bank_transfer'
            && (
                empty($validated['bank_name'])
                || empty($validated['account_number'])
                || empty($validated['ifsc_code'])
                || empty($validated['account_holder'])
            )
        ) {
            return back()->withErrors(['refund_method' => 'Please enter complete bank details for refund.'])->withInput();
        }
        if ($validated['type'] === 'return' && $validated['refund_method'] === 'upi' && empty($validated['upi_id'])) {
            return back()->withErrors(['upi_id' => 'Please enter your UPI ID for refund.'])->withInput();
        }

        try {
            DB::transaction(function () use ($validated, $request, $order, $selectedRefundAccount) {
                // Create Return/Exchange
                $orderReturn = OrderReturn::create([
                    'order_id'       => $order->id,
                    'user_id'        => auth()->id(),
                    'type'           => $validated['type'],
                    'status'         => 'pending',
                    'reason'         => $validated['reason'],
                    'description'    => $validated['description'] ?? null,
                    'exchange_size'  => $validated['exchange_size'] ?? null,
                    'exchange_color' => $validated['exchange_color'] ?? null,
                ]);

                // Add all items from the order
                foreach ($order->items as $item) {
                    $orderReturn->items()->create([
                        'order_item_id' => $item->id,
                        'quantity'      => $item->quantity,
                        'reason'        => $validated['reason'],
                    ]);
                }

                // Create Refund record (only for returns, not exchanges)
                if ($validated['type'] === 'return') {
                    $shippingCharge = (float) $order->non_refundable_shipping_charge;
                    $savedRefundAccount = $selectedRefundAccount;

                    if (
                        !$savedRefundAccount
                        && in_array($validated['refund_method'], ['bank_transfer', 'upi'], true)
                        && ($request->boolean('save_refund_account') || $request->boolean('make_primary_refund_account'))
                    ) {
                        $makePrimary = $request->boolean('make_primary_refund_account')
                            || !UserRefundAccount::where('user_id', auth()->id())->exists();

                        if ($makePrimary) {
                            UserRefundAccount::where('user_id', auth()->id())->update(['is_primary' => false]);
                        }

                        $savedRefundAccount = UserRefundAccount::create([
                            'user_id'        => auth()->id(),
                            'type'           => $validated['refund_method'],
                            'label'          => $validated['refund_method'] === 'upi'
                                ? ($validated['upi_id'] ?? 'UPI')
                                : trim(($validated['bank_name'] ?? 'Bank') . ' ****' . substr((string) ($validated['account_number'] ?? ''), -4)),
                            'upi_id'         => $validated['upi_id'] ?? null,
                            'bank_name'      => $validated['bank_name'] ?? null,
                            'account_number' => $validated['account_number'] ?? null,
                            'ifsc_code'      => $validated['ifsc_code'] ?? null,
                            'account_holder' => $validated['account_holder'] ?? null,
                            'is_primary'     => $makePrimary,
                        ]);
                    } elseif ($savedRefundAccount && $request->boolean('make_primary_refund_account')) {
                        UserRefundAccount::where('user_id', auth()->id())->update(['is_primary' => false]);
                        $savedRefundAccount->update(['is_primary' => true]);
                    }

                    Refund::create([
                        'return_id'      => $orderReturn->id,
                        'order_id'       => $order->id,
                        'user_id'        => auth()->id(),
                        'amount'         => $order->refundable_amount,
                        'method'         => $validated['refund_method'],
                        'status'         => 'pending',
                        'upi_id'         => $validated['upi_id'] ?? null,
                        'bank_name'      => $validated['bank_name'] ?? null,
                        'account_number' => $validated['account_number'] ?? null,
                        'ifsc_code'      => $validated['ifsc_code'] ?? null,
                        'account_holder' => $validated['account_holder'] ?? null,
                        'notes'          => 'Refund requested by customer on ' . now()->format('d M Y H:i') . '. Delivery charges of ₹' . number_format($shippingCharge, 2) . ' are non-refundable.',
                    ]);
                }
            });
        } catch (\Throwable $e) {
            Log::error('Return store error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.');
        }

        // Activity log
        try {
            \App\Helpers\ActivityLogger::log(
                'return_requested',
                ucfirst($validated['type']) . " request for Order #{$order->order_number}",
                ['order_id' => $order->id, 'type' => $validated['type'], 'reason' => $validated['reason']]
            );
        } catch (\Throwable $e) {}

        return redirect()->route('order.returns')
            ->with('success', ucfirst($validated['type']) . ' request submitted successfully! We will process it within 2–3 business days.');
    }

    // ── GET /my-returns/{return} ─────────────────────────────
    public function show(OrderReturn $return)
    {
        if ($return->user_id !== auth()->id()) abort(403);
        $return->load(['order.items.product', 'refund']);
        return view('froentend.returns.show', compact('return'));
    }

    private function canRefundToOriginalPayment(Order $order): bool
    {
        $method = strtolower((string) $order->payment_method);

        return $order->payment_status === 'paid'
            && !in_array($method, ['', 'cod'], true)
            && (
                !empty($order->payment_id)
                || !empty($order->razorpay_order_id)
                || !empty(optional($order->payment)->payment_id)
                || !empty(optional($order->payment)->gateway_order_id)
            );
    }

    private function exchangeOptionsForOrder(Order $order): array
    {
        $order->loadMissing('items.product.variants.size', 'items.product.variants.color');

        $products = $order->items
            ->pluck('product')
            ->filter()
            ->unique('id')
            ->values();

        $sizes = collect();
        $colors = collect();
        $colorGroupIds = $products
            ->map(fn ($product) => $product->parent_product_id ?: $product->id)
            ->filter()
            ->unique()
            ->values();

        foreach ($order->items as $item) {
            if (!empty($item->size)) {
                $sizes->push($item->size);
            }
            if (!empty($item->color)) {
                $colors->push($item->color);
            }
        }

        foreach ($products as $product) {
            if (!empty($product->color_name)) {
                $colors->push($product->color_name);
            }

            $variantQuery = $product->variants()
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('stock')->orWhere('stock', '>', 0);
                });

            $variantQuery->get()->each(function ($variant) use ($sizes, $colors) {
                if (!empty($variant->size)) {
                    $sizes->push($variant->size);
                } elseif ($variant->size_id && $variant->relationLoaded('size') && $variant->getRelation('size')) {
                    $sizes->push($variant->getRelation('size')->name);
                }

                if (!empty($variant->color)) {
                    $colors->push($variant->color);
                } elseif ($variant->color_id && $variant->relationLoaded('color') && $variant->getRelation('color')) {
                    $colors->push($variant->getRelation('color')->name);
                }
            });
        }

        if ($colorGroupIds->isNotEmpty()) {
            Product::where('is_active', true)
                ->where(function ($query) use ($colorGroupIds) {
                    $query->whereIn('id', $colorGroupIds)
                        ->orWhereIn('parent_product_id', $colorGroupIds);
                })
                ->pluck('color_name')
                ->filter()
                ->each(fn ($color) => $colors->push($color));
        }

        $sizeOrder = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', '3XL', '4XL', '5XL'];

        $sizes = $sizes->filter()
            ->map(fn ($size) => trim((string) $size))
            ->filter()
            ->unique()
            ->sortBy(fn ($size) => array_search(strtoupper($size), $sizeOrder, true) !== false ? array_search(strtoupper($size), $sizeOrder, true) : 999)
            ->values();

        $colors = $colors->filter()
            ->map(fn ($color) => trim((string) $color))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return [$sizes, $colors];
    }
}
