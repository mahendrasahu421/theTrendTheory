<?php
// app/Http/Controllers/Admin/SuperAdminDashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Order, User, Employee, InventoryLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // ── FILTER ────────────────────────────────────
        $filter = $request->get('filter', 'monthly');
        [$startDate, $endDate, $prevStart, $prevEnd] = $this->getDateRange($filter, $request);

        // ── REVENUE ───────────────────────────────────
        $revenue     = Order::where('payment_status','paid')->whereBetween('created_at',[$startDate,$endDate])->sum('total_amount') ?? 0;
        $prevRevenue = Order::where('payment_status','paid')->whereBetween('created_at',[$prevStart,$prevEnd])->sum('total_amount') ?? 1;
        $revGrowth   = round((($revenue - $prevRevenue) / max($prevRevenue,1)) * 100);

        // ── ORDERS ────────────────────────────────────
        $orders     = Order::whereBetween('created_at',[$startDate,$endDate])->count();
        $prevOrders = Order::whereBetween('created_at',[$prevStart,$prevEnd])->count() ?: 1;
        $ordGrowth  = round((($orders - $prevOrders) / max($prevOrders,1)) * 100);

        // ── CUSTOMERS ─────────────────────────────────
        $newCustomers   = User::where('role','customer')->whereBetween('created_at',[$startDate,$endDate])->count();
        $totalCustomers = User::where('role','customer')->count();
        $repeatRate     = $this->getRepeatRate();
        $ltv            = $totalCustomers > 0 ? round((Order::where('payment_status','paid')->sum('total_amount') / $totalCustomers) * 1.8) : 0;

        // ── AOV & MARGINS ─────────────────────────────
        $orderCount  = max(Order::where('payment_status','paid')->count(), 1);
        $totalRev    = (float) Order::where('payment_status','paid')->sum('total_amount');
        $aov         = round($totalRev / $orderCount);
        $grossMargin = 56;
        $ebitda      = 25;

        // ── TREND CHART ───────────────────────────────
        $chartData = $this->getChartData($filter, $request);

        // ── CATEGORY REVENUE ─────────────────────────
        $categoryRevenue = DB::table('order_items')
            ->join('products','order_items.product_id','=','products.id')
            ->join('categories','products.category_id','=','categories.id')
            ->whereNull('categories.parent_id')
            ->whereBetween('order_items.created_at',[$startDate,$endDate])
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('categories.id','categories.name')
            ->orderByDesc('revenue')->get();

        // ── TOP PRODUCTS ──────────────────────────────
        $topProducts = Product::with('category')
            ->where('is_active',true)
            ->orderByDesc('total_sold')
            ->limit(5)->get();

        // ── ORDER STATUS ──────────────────────────────
        $orderStatus = Order::whereBetween('created_at',[$startDate,$endDate])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')->get();

        // ── INVESTOR METRICS ──────────────────────────
        $cac       = 480;
        $ltvCac    = $cac > 0 ? round($ltv / $cac, 1) : 0;
        $tam       = '₹2.1L Cr';
        $marketGrowth = '27%';

        return view('admin.dashboards.super_admin', compact(
            'filter','startDate','endDate',
            'revenue','prevRevenue','revGrowth',
            'orders','prevOrders','ordGrowth',
            'newCustomers','totalCustomers','repeatRate',
            'ltv','aov','grossMargin','ebitda',
            'chartData','categoryRevenue','topProducts','orderStatus',
            'cac','ltvCac','tam','marketGrowth'
        ));
    }

    private function getDateRange(string $filter, Request $request): array
    {
        return match($filter) {
            'daily' => [
                now()->startOfDay(), now()->endOfDay(),
                now()->subDay()->startOfDay(), now()->subDay()->endOfDay(),
            ],
            'weekly' => [
                now()->startOfWeek(), now()->endOfWeek(),
                now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek(),
            ],
            'quarterly' => [
                now()->startOfQuarter(), now()->endOfQuarter(),
                now()->subQuarter()->startOfQuarter(), now()->subQuarter()->endOfQuarter(),
            ],
            'yearly' => [
                now()->startOfYear(), now()->endOfYear(),
                now()->subYear()->startOfYear(), now()->subYear()->endOfYear(),
            ],
            'custom' => [
                Carbon::parse($request->from ?? now()->subMonth()),
                Carbon::parse($request->to ?? now()),
                Carbon::parse($request->from ?? now()->subMonth())->subMonth(),
                Carbon::parse($request->to ?? now())->subMonth(),
            ],
            default => [ // monthly
                now()->startOfMonth(), now()->endOfMonth(),
                now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth(),
            ],
        };
    }

    private function getChartData(string $filter, Request $request): array
    {
        $groupBy = match($filter) {
            'daily'     => 'HOUR(created_at)',
            'weekly'    => 'DATE(created_at)',
            'quarterly' => 'MONTH(created_at)',
            'yearly'    => 'MONTH(created_at)',
            default     => 'DATE(created_at)',
        };

        [$start, $end] = $this->getDateRange($filter, $request);

        $data = Order::where('payment_status','paid')
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw($groupBy.' as period'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels'  => $data->pluck('period')->toArray(),
            'revenue' => $data->map(fn($d) => round((float)$d->revenue))->toArray(),
            'orders'  => $data->pluck('orders')->toArray(),
        ];
    }

    private function getRepeatRate(): int
    {
        $total  = User::where('role','customer')->count();
        $repeat = DB::table('orders')->select('user_id')->whereNotNull('user_id')->groupBy('user_id')->havingRaw('COUNT(*) > 1')->get()->count();
        return $total > 0 ? round(($repeat / $total) * 100) : 0;
    }
}