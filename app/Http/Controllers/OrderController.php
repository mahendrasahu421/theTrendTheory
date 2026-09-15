<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Customer-facing: Cancel an order.
     * Only allowed if status is 'pending' or 'confirmed' (not shipped/delivered/cancelled).
     */
    public function cancel(Request $request, Order $order)
    {
        // Security: ensure this order belongs to the logged-in user
        if ($order->user_id !== auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

        // Only allow cancel if not already shipped/delivered/cancelled
        $nonCancellableStatuses = ['shipped', 'out_for_delivery', 'delivered', 'cancelled'];
        if (in_array(strtolower($order->status), $nonCancellableStatuses)) {
            $msg = match (strtolower($order->status)) {
                'cancelled'        => 'This order is already cancelled.',
                'delivered'        => 'Delivered orders cannot be cancelled. Please contact support for a return.',
                'shipped',
                'out_for_delivery' => 'Order has already been shipped and cannot be cancelled. You may refuse the delivery.',
                default            => 'This order cannot be cancelled at this stage.',
            };
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $reason = trim($request->input('cancel_reason', 'Cancelled by customer'));

        // Update order status to cancelled
        $order->update([
            'status' => 'cancelled',
            'notes'  => ($order->notes ? $order->notes . "\n" : '') .
                        '[CANCELLED] Reason: ' . $reason . ' (by customer on ' . now()->format('d M Y H:i') . ')',
        ]);

        // Restore product stock
        foreach ($order->items as $item) {
            \App\Models\Product::where('id', $item->product_id)
                ->increment('stock', (int) $item->quantity);
            \App\Models\Product::where('id', $item->product_id)
                ->when((int) $item->quantity > 0, fn ($q) => $q->decrement('total_sold', (int) $item->quantity));
        }

        // Log activity
        try {
            \App\Helpers\ActivityLogger::log(
                'order_cancelled',
                "Cancelled Order #{$order->order_number} — Reason: {$reason}",
                ['order_id' => $order->id, 'order_number' => $order->order_number, 'reason' => $reason]
            );
        } catch (\Throwable $e) {
            Log::warning('ActivityLogger error on cancel: ' . $e->getMessage());
        }

        // Send cancellation notification (email / WhatsApp) — graceful fail
        try {
            $ns = app(\App\Services\NotificationService::class);
            if (method_exists($ns, 'sendOrderCancelledNotification')) {
                $ns->sendOrderCancelledNotification($order);
            }
        } catch (\Throwable $e) {
            Log::warning('Cancel notification error: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => "Order #{$order->order_number} has been cancelled successfully.",
                'redirect' => route('order.track'),
            ]);
        }

        return redirect()->route('order.track')
            ->with('success', "Order #{$order->order_number} cancelled successfully. Stock has been restored.");
    }
}
