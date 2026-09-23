<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics and visitor tracking dashboard.
     */
    public function index(Request $request)
    {
        $range = $request->query('range', '7days');
        $sourceFilter = $request->query('source');
        $stateFilter = $request->query('state');
        $cityFilter = $request->query('city');
        $deviceFilter = $request->query('device');
        $brandFilter = $request->query('brand');
        $search = $request->query('search');

        // Date filter
        $now = Carbon::now();
        $startDate = match ($range) {
            'today'     => $now->copy()->startOfDay(),
            'yesterday' => $now->copy()->subDay()->startOfDay(),
            '30days'    => $now->copy()->subDays(29)->startOfDay(),
            'this_month'=> $now->copy()->startOfMonth(),
            'all'       => Carbon::create(2020, 1, 1),
            default     => $now->copy()->subDays(6)->startOfDay(), // 7days default
        };

        $endDate = match ($range) {
            'yesterday' => $now->copy()->subDay()->endOfDay(),
            default     => $now->copy()->endOfDay(),
        };

        // Base Query
        $baseQuery = VisitorLog::query()
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($sourceFilter) {
            $baseQuery->where('source', $sourceFilter);
        }
        if ($stateFilter) {
            $baseQuery->where('state', $stateFilter);
        }
        if ($cityFilter) {
            $baseQuery->where('city', $cityFilter);
        }
        if ($deviceFilter) {
            $baseQuery->where('device_type', $deviceFilter);
        }
        if ($brandFilter) {
            $baseQuery->where('device_brand', $brandFilter);
        }
        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%")
                    ->orWhere('landing_page', 'like', "%{$search}%")
                    ->orWhere('device_model', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // ── 1. Top Level Metrics ─────────────────────────────
        $totalVisits = (clone $baseQuery)->count();
        $uniqueVisitors = (clone $baseQuery)->distinct('ip_address')->count('ip_address');
        $totalPageViews = (clone $baseQuery)->sum('page_views_count');
        
        $activeVisitorsCount = VisitorLog::where('last_activity_at', '>=', now()->subMinutes(5))->count();

        // ── 2. Traffic Sources Breakdown ─────────────────────
        $topSources = (clone $baseQuery)
            ->select('source', DB::raw('COUNT(*) as total'))
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        // ── 3. Geographic Breakdown (States & Cities) ────────
        $topStates = (clone $baseQuery)
            ->whereNotNull('state')
            ->where('state', '!=', '')
            ->where('state', '!=', 'Unknown')
            ->select('state', 'country', DB::raw('COUNT(*) as total'))
            ->groupBy('state', 'country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topCities = (clone $baseQuery)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->where('city', '!=', 'Unknown')
            ->select('city', 'state', DB::raw('COUNT(*) as total'))
            ->groupBy('city', 'state')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── 4. Devices & Phone Models Breakdown ──────────────
        $topDeviceTypes = (clone $baseQuery)
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        $topPhoneBrands = (clone $baseQuery)
            ->whereNotNull('device_brand')
            ->where('device_brand', '!=', '')
            ->where('device_brand', '!=', 'Unknown')
            ->select('device_brand', DB::raw('COUNT(*) as total'))
            ->groupBy('device_brand')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $topBrowsers = (clone $baseQuery)
            ->whereNotNull('browser')
            ->where('browser', '!=', '')
            ->where('browser', '!=', 'Unknown')
            ->select('browser', DB::raw('COUNT(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // ── 5. Real-time / Filtered Logs Table ───────────────
        $logs = (clone $baseQuery)
            ->with('user')
            ->orderByDesc('last_activity_at')
            ->paginate(25)
            ->withQueryString();

        // Filter Dropdown options
        $availableSources = VisitorLog::distinct('source')->whereNotNull('source')->pluck('source');
        $availableStates = VisitorLog::distinct('state')->whereNotNull('state')->where('state', '!=', 'Unknown')->pluck('state');

        return view('admin.analytics.index', compact(
            'logs',
            'range',
            'totalVisits',
            'uniqueVisitors',
            'totalPageViews',
            'activeVisitorsCount',
            'topSources',
            'topStates',
            'topCities',
            'topDeviceTypes',
            'topPhoneBrands',
            'topBrowsers',
            'availableSources',
            'availableStates',
            'sourceFilter',
            'stateFilter',
            'cityFilter',
            'deviceFilter',
            'brandFilter',
            'search'
        ));
    }

    /**
     * Export Visitor Logs to CSV.
     */
    public function export(Request $request)
    {
        $fileName = 'visitor_analytics_' . date('Y-m-d_H-i') . '.csv';

        $logs = VisitorLog::with('user')
            ->orderByDesc('created_at')
            ->limit(5000)
            ->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = [
            'Date Time', 'IP Address', 'City', 'State', 'Country', 'Source / Platform',
            'Referrer URL', 'Landing Page', 'Device Type', 'Phone Brand', 'Device Model',
            'OS', 'Browser', 'User ID', 'Customer Name', 'Page Views'
        ];

        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->ip_address,
                    $log->city,
                    $log->state,
                    $log->country,
                    $log->source,
                    $log->referrer_url,
                    $log->landing_page,
                    $log->device_type,
                    $log->device_brand,
                    $log->device_model,
                    $log->os . ' ' . $log->os_version,
                    $log->browser,
                    $log->user_id ?? 'Guest',
                    $log->user ? $log->user->name : 'Anonymous Visitor',
                    $log->page_views_count,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * SuperAdmin: Clean up older visitor logs.
     */
    public function clearOldLogs(Request $request)
    {
        $adminUser = auth('admin')->user() ?? auth()->user();
        if (!$adminUser || !$adminUser->isSuperAdmin()) {
            abort(403, 'Only Super Admin can purge analytics logs.');
        }

        $days = (int) $request->input('days', 60);
        $deleted = VisitorLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->back()->with('success', "Successfully purged {$deleted} visitor log records older than {$days} days.");
    }

    /**
     * Display live customer activity stream with outreach triggers.
     */
    public function activities(Request $request)
    {
        $eventType = $request->query('event_type');
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 30);
        if (!in_array($perPage, [15, 30, 50, 100])) {
            $perPage = 30;
        }

        $query = \App\Models\UserActivity::with('user')
            ->orderByDesc('created_at');

        if ($eventType) {
            $query->where('event_type', $eventType);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('event_title', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $activities = $query->paginate($perPage)->withQueryString();

        $eventCounts = [
            'all'               => \App\Models\UserActivity::count(),
            'checkout_started'  => \App\Models\UserActivity::where('event_type', 'checkout_started')->count(),
            'cart_added'        => \App\Models\UserActivity::where('event_type', 'cart_added')->count(),
            'product_viewed'    => \App\Models\UserActivity::where('event_type', 'product_viewed')->count(),
            'order_placed'      => \App\Models\UserActivity::where('event_type', 'order_placed')->count(),
        ];

        if ($request->ajax() || $request->wantsJson()) {
            $first = $activities->firstItem() ?? 0;
            $last = $activities->lastItem() ?? 0;
            $total = $activities->total();
            $showingText = $total > 0
                ? "Showing <b>{$first}–{$last}</b> of <b>{$total}</b> activity events"
                : "Showing <b>0</b> activity events";
            $showingFooter = $total > 0
                ? "Showing <strong>{$first}</strong> to <strong>{$last}</strong> of <strong>{$total}</strong> activities"
                : "Showing <strong>0</strong> activities";

            return response()->json([
                'success'         => true,
                'list_html'       => view('admin.analytics.partials.activities_list', compact('activities'))->render(),
                'pagination_html' => view('admin.analytics.partials.pagination', compact('activities'))->render(),
                'total_count'     => $total,
                'current_page'    => $activities->currentPage(),
                'last_page'       => $activities->lastPage(),
                'showing_text'    => $showingText,
                'showing_footer'  => $showingFooter,
                'event_counts'    => $eventCounts,
            ]);
        }

        return view('admin.analytics.activities', compact('activities', 'eventType', 'search', 'eventCounts', 'perPage'));
    }

    /**
     * Send direct outreach notification (Email / Log WhatsApp outreach).
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'user_id'     => 'nullable|integer',
            'activity_id' => 'nullable|integer',
            'channel'     => 'required|in:whatsapp,email',
            'email'       => 'nullable|email',
            'phone'       => 'nullable|string',
            'subject'     => 'nullable|string|max:200',
            'message'     => 'required|string',
        ]);

        $channel = $request->input('channel');
        $user = $request->user_id ? \App\Models\User::find($request->user_id) : null;
        $email = $request->email ?: ($user ? $user->email : null);
        $phone = $request->phone ?: ($user ? $user->phone : null);

        if ($request->activity_id) {
            \App\Models\UserActivity::where('id', $request->activity_id)->update([
                'contacted_at'      => now(),
                'contacted_channel' => $channel,
            ]);
        }

        if ($channel === 'email') {
            if (!$email) {
                return response()->json(['success' => false, 'message' => 'Customer email address is required.'], 422);
            }

            try {
                \Illuminate\Support\Facades\Mail::raw($request->message, function ($m) use ($email, $request) {
                    $m->to($email)
                      ->subject($request->subject ?: 'Message from ' . config('app.name', 'THE TREND THEORY'));
                });
            } catch (\Throwable $e) {
                report($e);
            }

            return response()->json([
                'success' => true,
                'message' => 'Email notification sent successfully to ' . $email . '!',
            ]);
        }

        // WhatsApp action
        return response()->json([
            'success' => true,
            'message' => 'WhatsApp outreach recorded.',
            'whatsapp_url' => 'https://wa.me/' . preg_replace('/[^0-9]/', '', (string)$phone) . '?text=' . urlencode($request->message),
        ]);
    }
}
