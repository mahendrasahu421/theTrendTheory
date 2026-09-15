<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Refund;
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

        $pincodeService = app(\App\Services\PincodeService::class);
        $pincodeCheck = $pincodeService->checkPincode((string)$order->shipping_pincode);
        $isExchangeOnly = (bool)($pincodeCheck['is_exchange_only'] ?? false);

        return view('froentend.returns.create', compact('order', 'isExchangeOnly', 'pincodeCheck'));
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
        ]);

        // Validate bank/UPI fields based on method
        if ($validated['refund_method'] === 'bank_transfer') {
            $request->validate([
                'bank_name'      => 'required|string|max:80',
                'account_number' => 'required|string|max:40',
                'ifsc_code'      => 'required|string|max:20',
                'account_holder' => 'required|string|max:120',
            ]);
        }
        if ($validated['refund_method'] === 'upi') {
            $request->validate(['upi_id' => 'required|string|max:120']);
        }

        try {
            DB::transaction(function () use ($validated, $request, $order) {
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
                    Refund::create([
                        'return_id'      => $orderReturn->id,
                        'order_id'       => $order->id,
                        'user_id'        => auth()->id(),
                        'amount'         => $order->total_amount,
                        'method'         => $validated['refund_method'],
                        'status'         => 'pending',
                        'upi_id'         => $validated['upi_id'] ?? null,
                        'bank_name'      => $validated['bank_name'] ?? null,
                        'account_number' => $validated['account_number'] ?? null,
                        'ifsc_code'      => $validated['ifsc_code'] ?? null,
                        'account_holder' => $validated['account_holder'] ?? null,
                        'notes'          => 'Refund requested by customer on ' . now()->format('d M Y H:i'),
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
}

