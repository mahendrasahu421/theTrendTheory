<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    /**
     * Display a listing of newsletter subscribers.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = trim((string) $request->input('search', ''));
        $sort   = $request->input('sort', 'latest');

        // Query
        $query = NewsletterSubscriber::query();

        if ($search !== '') {
            $query->search($search);
        }

        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'inactive') {
            $query->inactive();
        }

        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $subscribers = $query->paginate(20)->withQueryString();

        // KPI Counts
        $kpi = [
            'total'     => NewsletterSubscriber::count(),
            'active'    => NewsletterSubscriber::active()->count(),
            'inactive'  => NewsletterSubscriber::inactive()->count(),
            'today'     => NewsletterSubscriber::whereDate('created_at', today())->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'kpi', 'status', 'search', 'sort'));
    }

    /**
     * Store a newly created subscriber from admin panel.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email:filter', 'max:191', 'unique:newsletter_subscribers,email'],
            'is_active' => ['nullable', 'boolean'],
            'source' => ['nullable', 'string', 'max:100'],
        ], [
            'email.required' => 'Email address is required.',
            'email.email'    => 'Please enter a valid email address.',
            'email.unique'   => 'This email address is already subscribed.',
        ]);

        NewsletterSubscriber::create([
            'email'      => strtolower(trim($validated['email'])),
            'is_active'  => $request->boolean('is_active', true),
            'source'     => $validated['source'] ?: 'admin',
            'ip_address' => $request->ip(),
            'user_agent' => 'Admin Panel',
        ]);

        return redirect()->route('admin.newsletter.index')->with('success', 'Subscriber added successfully!');
    }

    /**
     * Toggle active/inactive status of a subscriber.
     */
    public function toggle(Request $request, NewsletterSubscriber $subscriber)
    {
        $newStatus = !$subscriber->is_active;

        $subscriber->update([
            'is_active'       => $newStatus,
            'unsubscribed_at' => $newStatus ? null : now(),
        ]);

        $message = $newStatus 
            ? "Subscriber '{$subscriber->email}' has been activated." 
            : "Subscriber '{$subscriber->email}' has been deactivated.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $subscriber->is_active,
                'message'   => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified subscriber from storage.
     */
    public function destroy(Request $request, NewsletterSubscriber $subscriber)
    {
        $email = $subscriber->email;
        $subscriber->delete();

        $message = "Subscriber '{$email}' has been deleted.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.newsletter.index')->with('success', $message);
    }

    /**
     * Bulk actions (activate, deactivate, delete).
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete'],
            'ids'    => ['required', 'array'],
            'ids.*'  => ['exists:newsletter_subscribers,id'],
        ]);

        $ids = $validated['ids'];
        $count = count($ids);

        switch ($validated['action']) {
            case 'activate':
                NewsletterSubscriber::whereIn('id', $ids)->update([
                    'is_active'       => true,
                    'unsubscribed_at' => null,
                ]);
                $msg = "{$count} subscribers activated successfully.";
                break;

            case 'deactivate':
                NewsletterSubscriber::whereIn('id', $ids)->update([
                    'is_active'       => false,
                    'unsubscribed_at' => now(),
                ]);
                $msg = "{$count} subscribers deactivated successfully.";
                break;

            case 'delete':
                NewsletterSubscriber::whereIn('id', $ids)->delete();
                $msg = "{$count} subscribers deleted successfully.";
                break;
        }

        return redirect()->route('admin.newsletter.index')->with('success', $msg);
    }

    /**
     * Export subscribers as CSV file.
     */
    public function export(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = trim((string) $request->input('search', ''));

        $query = NewsletterSubscriber::query();

        if ($search !== '') {
            $query->search($search);
        }

        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'inactive') {
            $query->inactive();
        }

        $query->latest();

        $filename = 'subscribers_' . date('Y-m-d_H-i-s') . '.csv';

        return new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Header Row
            fputcsv($handle, ['ID', 'Email', 'Status', 'Source', 'IP Address', 'Joined Date', 'Unsubscribed Date']);

            $query->chunk(200, function ($subscribers) use ($handle) {
                foreach ($subscribers as $sub) {
                    fputcsv($handle, [
                        $sub->id,
                        $sub->email,
                        $sub->is_active ? 'Active' : 'Inactive',
                        $sub->source ?: 'footer',
                        $sub->ip_address ?: 'N/A',
                        $sub->created_at ? $sub->created_at->format('Y-m-d H:i:s') : 'N/A',
                        $sub->unsubscribed_at ? $sub->unsubscribed_at->format('Y-m-d H:i:s') : 'N/A',
                    ]);
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
