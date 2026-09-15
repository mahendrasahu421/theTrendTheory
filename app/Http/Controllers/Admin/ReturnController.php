<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderReturn::with(['order.items.product', 'user', 'refund'])->latest();

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Type Filter (return / exchange)
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Search Filter (Return #, Order #, Customer Name, Email)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('order_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $returns = $query->paginate(15)->withQueryString();

        // Summary Stats
        $stats = [
            'total'     => OrderReturn::count(),
            'pending'   => OrderReturn::where('status', 'pending')->count(),
            'approved'  => OrderReturn::where('status', 'approved')->count(),
            'completed' => OrderReturn::where('status', 'completed')->count(),
            'refunded'  => Refund::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.returns.index', compact('returns', 'stats'));
    }

    public function show(OrderReturn $return)
    {
        $return->load(['order.items.product', 'user', 'refund']);
        return view('admin.returns.show', compact('return'));
    }

    public function updateStatus(Request $request, OrderReturn $return)
    {
        $validated = $request->validate([
            'status'      => 'required|in:pending,approved,picked_up,received,completed,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $return->update([
            'status'      => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $return->admin_notes
        ]);

        // Auto-update refund status if return is completed or rejected
        if ($return->refund) {
            if ($validated['status'] === 'completed' && $return->refund->status === 'pending') {
                $return->refund->update(['status' => 'processing']);
            } elseif ($validated['status'] === 'rejected') {
                $return->refund->update(['status' => 'failed', 'notes' => 'Return request rejected: ' . ($validated['admin_notes'] ?? '')]);
            }
        }

        try {
            \App\Helpers\ActivityLogger::log(
                'return_status_updated',
                "Return #{$return->return_number} status updated to " . ucfirst($validated['status']),
                ['return_id' => $return->id, 'new_status' => $validated['status']]
            );
        } catch (\Throwable $e) {}

        return back()->with('success', "Return #{$return->return_number} status updated to " . ucwords(str_replace('_', ' ', $validated['status'])));
    }

    public function updateRefund(Request $request, OrderReturn $return)
    {
        if (!$return->refund) {
            return back()->with('error', 'No refund record found for this return.');
        }

        $validated = $request->validate([
            'status'    => 'required|in:pending,processing,completed,failed',
            'refund_id' => 'nullable|string|max:120',
            'notes'     => 'nullable|string|max:500'
        ]);

        $updateData = [
            'status'    => $validated['status'],
            'refund_id' => $validated['refund_id'] ?? $return->refund->refund_id,
            'notes'     => $validated['notes'] ?? $return->refund->notes,
        ];

        if ($validated['status'] === 'completed' && empty($return->refund->processed_at)) {
            $updateData['processed_at'] = now();
        }

        $return->refund->update($updateData);

        return back()->with('success', "Refund status updated to " . ucfirst($validated['status']));
    }
}