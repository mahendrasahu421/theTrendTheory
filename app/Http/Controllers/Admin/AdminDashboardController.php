<?php
// app/Http/Controllers/Admin/AdminDashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Order, Employee, Attendance, InventoryLog};
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ── ORDERS ────────────────────────────────────
        $totalOrders   = Order::count();
        $todayOrders   = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $monthRevenue  = (float)(Order::where('payment_status','paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount') ?? 0);
        $recentOrders  = Order::with('user')->latest()->limit(10)->get();

        // ── INVENTORY ─────────────────────────────────
        $totalProducts   = Product::where('is_active', true)->count();
        $lowStock        = Product::where('is_active', true)->where('stock', '<=', 5)->count();
        $outOfStock      = Product::where('is_active', true)->where('stock', 0)->count();
        $totalStockValue = (float)(Product::where('is_active', true)
            ->selectRaw('SUM(stock * cost_price) as val')
            ->value('val') ?? 0);

        $lowStockProducts = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(10)->get();

        $recentLogs = InventoryLog::with(['product', 'user'])
            ->latest()->limit(10)->get();

        // ── EMPLOYEES ─────────────────────────────────
        $totalEmployees = Employee::where('status', 'active')->count();
        $presentToday   = Attendance::where('date', today())->where('status', 'present')->count();
        $absentToday    = Attendance::where('date', today())->where('status', 'absent')->count();
        $onLeaveToday   = Attendance::where('date', today())->where('status', 'on_leave')->count();
        $notMarkedToday = $totalEmployees - Attendance::where('date', today())->count();

        $employees = Employee::with([
            'attendance' => fn($q) => $q->whereMonth('date', now()->month)->whereYear('date', now()->year)
        ])
        ->where('status', 'active')
        ->withCount([
            'attendance as present_count' => fn($q) => $q->where('status','present')
                ->whereMonth('date', now()->month)->whereYear('date', now()->year)
        ])
        ->orderBy('name')->get();

        return view('admin.dashboards.admin', compact(
            'totalOrders','todayOrders','pendingOrders','monthRevenue','recentOrders',
            'totalProducts','lowStock','outOfStock','totalStockValue','lowStockProducts','recentLogs',
            'totalEmployees','presentToday','absentToday','onLeaveToday','notMarkedToday','employees'
        ));
    }
}