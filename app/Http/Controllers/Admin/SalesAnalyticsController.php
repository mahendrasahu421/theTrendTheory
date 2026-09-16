<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->query('range', 'this_month');
        $customStart = $request->query('start_date');
        $customEnd = $request->query('end_date');

        $now = Carbon::now();

        // 1. Calculate Current Period Dates
        if ($customStart && $customEnd) {
            $startDate = Carbon::parse($customStart)->startOfDay();
            $endDate = Carbon::parse($customEnd)->endOfDay();
            $rangeLabel = $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y');
            $periodDays = max(1, $startDate->diffInDays($endDate) + 1);
            $prevStartDate = $startDate->copy()->subDays($periodDays);
            $prevEndDate = $startDate->copy()->subSecond();
        } else {
            switch ($range) {
                case 'today':
                    $startDate = $now->copy()->startOfDay();
                    $endDate = $now->copy()->endOfDay();
                    $rangeLabel = 'Today (' . $now->format('M d, Y') . ')';
                    $prevStartDate = $now->copy()->subDay()->startOfDay();
                    $prevEndDate = $now->copy()->subDay()->endOfDay();
                    $periodDays = 1;
                    break;

                case 'yesterday':
                    $startDate = $now->copy()->subDay()->startOfDay();
                    $endDate = $now->copy()->subDay()->endOfDay();
                    $rangeLabel = 'Yesterday (' . $startDate->format('M d, Y') . ')';
                    $prevStartDate = $now->copy()->subDays(2)->startOfDay();
                    $prevEndDate = $now->copy()->subDays(2)->endOfDay();
                    $periodDays = 1;
                    break;

                case 'this_week':
                    $startDate = $now->copy()->startOfWeek();
                    $endDate = $now->copy()->endOfDay();
                    $rangeLabel = 'This Week (' . $startDate->format('M d') . ' - ' . $endDate->format('M d') . ')';
                    $prevStartDate = $startDate->copy()->subWeek();
                    $prevEndDate = $prevStartDate->copy()->addDays($startDate->diffInDays($endDate))->endOfDay();
                    $periodDays = max(1, $startDate->diffInDays($endDate) + 1);
                    break;

                case 'last_week':
                    $startDate = $now->copy()->subWeek()->startOfWeek();
                    $endDate = $now->copy()->subWeek()->endOfWeek();
                    $rangeLabel = 'Last Week (' . $startDate->format('M d') . ' - ' . $endDate->format('M d') . ')';
                    $prevStartDate = $startDate->copy()->subWeek();
                    $prevEndDate = $endDate->copy()->subWeek();
                    $periodDays = 7;
                    break;

                case '7days':
                    $startDate = $now->copy()->subDays(6)->startOfDay();
                    $endDate = $now->copy()->endOfDay();
                    $rangeLabel = 'Last 7 Days';
                    $prevStartDate = $startDate->copy()->subDays(7);
                    $prevEndDate = $startDate->copy()->subSecond();
                    $periodDays = 7;
                    break;

                case '30days':
                    $startDate = $now->copy()->subDays(29)->startOfDay();
                    $endDate = $now->copy()->endOfDay();
                    $rangeLabel = 'Last 30 Days';
                    $prevStartDate = $startDate->copy()->subDays(30);
                    $prevEndDate = $startDate->copy()->subSecond();
                    $periodDays = 30;
                    break;

                case 'last_month':
                    $startDate = $now->copy()->subMonth()->startOfMonth();
                    $endDate = $now->copy()->subMonth()->endOfMonth();
                    $rangeLabel = 'Last Month (' . $startDate->format('F Y') . ')';
                    $prevStartDate = $startDate->copy()->subMonth()->startOfMonth();
                    $prevEndDate = $startDate->copy()->subMonth()->endOfMonth();
                    $periodDays = $startDate->daysInMonth;
                    break;

                case 'this_quarter':
                    $startDate = $now->copy()->startOfQuarter();
                    $endDate = $now->copy()->endOfDay();
                    $quarterNum = ceil($now->month / 3);
                    $rangeLabel = 'This Quarter (Q' . $quarterNum . ' ' . $now->year . ')';
                    $prevStartDate = $startDate->copy()->subQuarter();
                    $prevEndDate = $prevStartDate->copy()->addDays($startDate->diffInDays($endDate))->endOfDay();
                    $periodDays = max(1, $startDate->diffInDays($endDate) + 1);
                    break;

                case 'last_quarter':
                    $startDate = $now->copy()->subQuarter()->startOfQuarter();
                    $endDate = $now->copy()->subQuarter()->endOfQuarter();
                    $quarterNum = ceil($startDate->month / 3);
                    $rangeLabel = 'Last Quarter (Q' . $quarterNum . ' ' . $startDate->year . ')';
                    $prevStartDate = $startDate->copy()->subQuarter()->startOfQuarter();
                    $prevEndDate = $startDate->copy()->subQuarter()->endOfQuarter();
                    $periodDays = $startDate->diffInDays($endDate) + 1;
                    break;

                case 'this_year':
                    $startDate = $now->copy()->startOfYear();
                    $endDate = $now->copy()->endOfDay();
                    $rangeLabel = 'This Year (' . $now->year . ')';
                    $prevStartDate = $now->copy()->subYear()->startOfYear();
                    $prevEndDate = $now->copy()->subYear()->endOfYear();
                    $periodDays = $startDate->diffInDays($endDate) + 1;
                    break;

                case 'last_year':
                    $startDate = $now->copy()->subYear()->startOfYear();
                    $endDate = $now->copy()->subYear()->endOfYear();
                    $rangeLabel = 'Last Year (' . $startDate->year . ')';
                    $prevStartDate = $now->copy()->subYears(2)->startOfYear();
                    $prevEndDate = $now->copy()->subYears(2)->endOfYear();
                    $periodDays = $startDate->diffInDays($endDate) + 1;
                    break;

                case 'all_time':
                    $firstOrder = Order::oldest()->first();
                    $startDate = $firstOrder ? $firstOrder->created_at->copy()->startOfDay() : $now->copy()->subYears(3)->startOfYear();
                    $endDate = $now->copy()->endOfDay();
                    $rangeLabel = 'All Time (' . $startDate->format('M Y') . ' - Present)';
                    $periodDays = max(1, $startDate->diffInDays($endDate) + 1);
                    $prevStartDate = $startDate->copy()->subDays($periodDays);
                    $prevEndDate = $startDate->copy()->subSecond();
                    break;

                case 'this_month':
                default:
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfDay();
                    $rangeLabel = 'This Month (' . $now->format('F Y') . ')';
                    $prevStartDate = $now->copy()->subMonth()->startOfMonth();
                    $prevEndDate = $now->copy()->subMonth()->endOfMonth();
                    $periodDays = max(1, $now->day);
                    $range = 'this_month';
                    break;
            }
        }

        // 2. Query Orders within Period
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        $orders = $ordersQuery->get();

        $prevOrders = Order::whereBetween('created_at', [$prevStartDate, $prevEndDate])->get();

        // 3. Financial Metrics
        $grossSales = (float) $orders->where('status', '!=', 'cancelled')->sum('total_amount');
        $prevGrossSales = (float) $prevOrders->where('status', '!=', 'cancelled')->sum('total_amount');
        $salesGrowth = $prevGrossSales > 0 ? round((($grossSales - $prevGrossSales) / $prevGrossSales) * 100, 1) : ($grossSales > 0 ? 100 : 0);

        $totalOrdersCount = $orders->count();
        $prevOrdersCount = $prevOrders->count();
        $ordersGrowth = $prevOrdersCount > 0 ? round((($totalOrdersCount - $prevOrdersCount) / $prevOrdersCount) * 100, 1) : ($totalOrdersCount > 0 ? 100 : 0);

        $validOrdersCount = $orders->where('status', '!=', 'cancelled')->count();
        $aov = $validOrdersCount > 0 ? round($grossSales / $validOrdersCount, 2) : 0;
        $prevValidOrdersCount = $prevOrders->where('status', '!=', 'cancelled')->count();
        $prevAov = $prevValidOrdersCount > 0 ? round($prevGrossSales / $prevValidOrdersCount, 2) : 0;
        $aovGrowth = $prevAov > 0 ? round((($aov - $prevAov) / $prevAov) * 100, 1) : 0;

        $totalDiscounts = (float) $orders->sum('discount_amount');
        $totalShippingCollected = (float) $orders->where('status', '!=', 'cancelled')->sum('shipping_charge');

        // Net Realized Revenue (Confirmed + Delivered orders)
        $netRealizedSales = (float) $orders->whereIn('status', ['confirmed', 'shipped', 'delivered'])->sum('total_amount');

        // 4. Payment Method Split (Prepaid vs COD)
        $prepaidOrders = $orders->filter(fn($o) => in_array(strtolower($o->payment_method ?? ''), ['razorpay', 'upi', 'online', 'card', 'netbanking']));
        $codOrders = $orders->filter(fn($o) => strtolower($o->payment_method ?? '') === 'cod' || empty($o->payment_method));
        $prepaidRevenue = (float) $prepaidOrders->where('status', '!=', 'cancelled')->sum('total_amount');
        $codRevenue = (float) $codOrders->where('status', '!=', 'cancelled')->sum('total_amount');
        $prepaidRatio = $grossSales > 0 ? round(($prepaidRevenue / $grossSales) * 100) : 0;
        $codRatio = 100 - $prepaidRatio;

        // 5. Customer Loyalty & Repeat Ratio
        $customerIds = $orders->pluck('user_id')->filter()->unique();
        $repeatCustomerCount = 0;
        if ($customerIds->isNotEmpty()) {
            $repeatCustomerCount = Order::whereIn('user_id', $customerIds)
                ->select('user_id')
                ->groupBy('user_id')
                ->havingRaw('count(*) > 1')
                ->get()
                ->count();
        }
        $repeatCustomerRatio = $customerIds->count() > 0 ? round(($repeatCustomerCount / $customerIds->count()) * 100) : 0;

        // 6. Strategic Sales Forecasting (30-Day Run Rate & Target Pacing)
        $dailyRunRate = $periodDays > 0 ? ($grossSales / $periodDays) : 0;
        $forecast30Days = round($dailyRunRate * 30);
        $monthlyTarget = 500000; // Default benchmark target ₹5,00,000
        $targetPacingPercent = round(($grossSales / $monthlyTarget) * 100);

        // 7. Time Series Chart Data (Daily / Hourly Breakdown)
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrderCount = [];

        if (in_array($range, ['today', 'yesterday'])) {
            // Hourly breakdown (00:00 - 23:00)
            for ($h = 0; $h < 24; $h++) {
                $chartLabels[] = sprintf('%02d:00', $h);
                $hourlyOrders = $orders->filter(fn($o) => $o->created_at->hour == $h);
                $chartRevenue[] = (float) $hourlyOrders->where('status', '!=', 'cancelled')->sum('total_amount');
                $chartOrderCount[] = $hourlyOrders->count();
            }
        } elseif ($periodDays > 120) {
            // Monthly breakdown for yearly / all-time
            $periodCursor = $startDate->copy()->startOfMonth();
            while ($periodCursor->lte($endDate)) {
                $monthKey = $periodCursor->format('Y-m');
                $chartLabels[] = $periodCursor->format('M Y');
                $monthOrders = $orders->filter(fn($o) => $o->created_at->format('Y-m') === $monthKey);
                $chartRevenue[] = (float) $monthOrders->where('status', '!=', 'cancelled')->sum('total_amount');
                $chartOrderCount[] = $monthOrders->count();
                $periodCursor->addMonth();
            }
        } elseif ($periodDays > 45) {
            // Weekly breakdown (every 7 days)
            $periodCursor = $startDate->copy();
            while ($periodCursor->lte($endDate)) {
                $weekEnd = $periodCursor->copy()->addDays(6);
                if ($weekEnd->gt($endDate)) $weekEnd = $endDate->copy();
                $chartLabels[] = $periodCursor->format('M d') . ' - ' . $weekEnd->format('M d');
                $startBound = $periodCursor->copy()->startOfDay();
                $endBound = $weekEnd->copy()->endOfDay();
                $weekOrders = $orders->filter(fn($o) => $o->created_at->gte($startBound) && $o->created_at->lte($endBound));
                $chartRevenue[] = (float) $weekOrders->where('status', '!=', 'cancelled')->sum('total_amount');
                $chartOrderCount[] = $weekOrders->count();
                $periodCursor->addDays(7);
            }
        } else {
            // Daily breakdown
            $periodCursor = $startDate->copy();
            while ($periodCursor->lte($endDate)) {
                $dateKey = $periodCursor->format('Y-m-d');
                $chartLabels[] = $periodCursor->format('M d');
                $dayOrders = $orders->filter(fn($o) => $o->created_at->format('Y-m-d') === $dateKey);
                $chartRevenue[] = (float) $dayOrders->where('status', '!=', 'cancelled')->sum('total_amount');
                $chartOrderCount[] = $dayOrders->count();
                $periodCursor->addDay();
            }
        }

        // 8. Peak Shopping Hours (00:00 to 23:00)
        $hourlyDistribution = array_fill(0, 24, 0);
        foreach ($orders as $o) {
            $hourlyDistribution[$o->created_at->hour]++;
        }
        $peakHour = array_keys($hourlyDistribution, max($hourlyDistribution ?: [0]))[0] ?? 20;
        $peakHourLabel = sprintf('%02d:00 - %02d:00', $peakHour, ($peakHour + 1) % 24);

        // 9. Top 10 Bestselling Products by Revenue in Period
        $orderIds = $orders->where('status', '!=', 'cancelled')->pluck('id');
        $topProducts = OrderItem::whereIn('order_id', $orderIds)
            ->select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(line_total) as total_revenue')
            )
            ->with('product')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_revenue')
            ->limit(8)
            ->get();

        // 10. Low-Stock Warnings for High Velocity Items
        $lowStockRisks = Product::where('stock', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->limit(6)
            ->get();

        // 11. Advanced COD vs Online Analytics Breakdown
        $codDelivered = $codOrders->where('status', 'delivered')->count();
        $codReturned = $codOrders->whereIn('status', ['returned', 'rto', 'cancelled'])->count();
        $codTotalCount = $codOrders->count();
        $codRealizationRate = $codTotalCount > 0 ? round(($codDelivered / $codTotalCount) * 100, 1) : 0;
        $codAov = $codTotalCount > 0 ? round($codRevenue / max(1, $codOrders->where('status', '!=', 'cancelled')->count()), 2) : 0;

        $prepaidDelivered = $prepaidOrders->where('status', 'delivered')->count();
        $prepaidReturned = $prepaidOrders->whereIn('status', ['returned', 'rto', 'cancelled'])->count();
        $prepaidTotalCount = $prepaidOrders->count();
        $prepaidRealizationRate = $prepaidTotalCount > 0 ? round(($prepaidDelivered / $prepaidTotalCount) * 100, 1) : 0;
        $prepaidAov = $prepaidTotalCount > 0 ? round($prepaidRevenue / max(1, $prepaidOrders->where('status', '!=', 'cancelled')->count()), 2) : 0;

        // 12. Top Revenue & Order Origin (States & Cities with COD vs Online Split)
        $topStatesData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('shipping_state')
            ->where('shipping_state', '!=', '')
            ->select(
                'shipping_state',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_amount) as state_revenue'),
                DB::raw("SUM(CASE WHEN LOWER(payment_method) = 'cod' OR payment_method IS NULL THEN 1 ELSE 0 END) as cod_count"),
                DB::raw("SUM(CASE WHEN LOWER(payment_method) != 'cod' AND payment_method IS NOT NULL THEN 1 ELSE 0 END) as online_count"),
                DB::raw("SUM(CASE WHEN LOWER(payment_method) = 'cod' OR payment_method IS NULL THEN total_amount ELSE 0 END) as cod_revenue"),
                DB::raw("SUM(CASE WHEN LOWER(payment_method) != 'cod' AND payment_method IS NOT NULL THEN total_amount ELSE 0 END) as online_revenue")
            )
            ->groupBy('shipping_state')
            ->orderByDesc('state_revenue')
            ->limit(6)
            ->get();

        $topCitiesData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('shipping_city')
            ->where('shipping_city', '!=', '')
            ->select(
                'shipping_city',
                'shipping_state',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_amount) as city_revenue'),
                DB::raw("SUM(CASE WHEN LOWER(payment_method) = 'cod' OR payment_method IS NULL THEN 1 ELSE 0 END) as cod_count"),
                DB::raw("SUM(CASE WHEN LOWER(payment_method) != 'cod' AND payment_method IS NOT NULL THEN 1 ELSE 0 END) as online_count")
            )
            ->groupBy('shipping_city', 'shipping_state')
            ->orderByDesc('city_revenue')
            ->limit(6)
            ->get();

        // 13. Customer COD Profiling (Top COD Shoppers, Frequency, Avg Spend & Risk)
        $codCustomers = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where(function($q) {
                $q->whereRaw("LOWER(payment_method) = 'cod'")->orWhereNull('payment_method');
            })
            ->select(
                'shipping_name',
                'shipping_phone',
                'shipping_city',
                'shipping_state',
                DB::raw('COUNT(*) as total_cod_orders'),
                DB::raw("SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_count"),
                DB::raw("SUM(CASE WHEN status IN ('returned', 'rto', 'cancelled') THEN 1 ELSE 0 END) as returned_count"),
                DB::raw('SUM(total_amount) as total_cod_spend'),
                DB::raw('AVG(total_amount) as avg_cod_order_value')
            )
            ->groupBy('shipping_name', 'shipping_phone', 'shipping_city', 'shipping_state')
            ->orderByDesc('total_cod_spend')
            ->limit(8)
            ->get()
            ->map(function ($c) {
                $retRate = $c->total_cod_orders > 0 ? round(($c->returned_count / $c->total_cod_orders) * 100) : 0;
                $risk = 'Safe';
                $riskColor = 'success';
                if ($retRate >= 40) {
                    $risk = 'High RTO Risk';
                    $riskColor = 'danger';
                } elseif ($retRate >= 20) {
                    $risk = 'Moderate Risk';
                    $riskColor = 'warning';
                }
                $c->return_rate = $retRate;
                $c->risk_level = $risk;
                $c->risk_color = $riskColor;
                return $c;
            });

        // 14. Returns & RTO Profit vs Loss Financial Impact
        $returnOrders = $orders->filter(fn($o) => in_array($o->status, ['returned', 'rto']));
        $returnCount = $returnOrders->count();
        $overallReturnRate = $totalOrdersCount > 0 ? round(($returnCount / $totalOrdersCount) * 100, 1) : 0;
        
        $codReturnCount = $codOrders->whereIn('status', ['returned', 'rto'])->count();
        $codReturnRate = $codTotalCount > 0 ? round(($codReturnCount / $codTotalCount) * 100, 1) : 0;
        
        $prepaidReturnCount = $prepaidOrders->whereIn('status', ['returned', 'rto'])->count();
        $prepaidReturnRate = $prepaidTotalCount > 0 ? round(($prepaidReturnCount / $prepaidTotalCount) * 100, 1) : 0;

        // Financial Loss on Returns:
        // Estimated Forward + Reverse shipping loss = ₹120 per RTO/Return
        // Estimated Packaging, restocking & damage loss = ₹30 per return
        $returnLogisticsLoss = $returnCount * 120;
        $returnPackagingLoss = $returnCount * 30;
        $totalReturnFinancialLoss = $returnLogisticsLoss + $returnPackagingLoss;
        $returnedMerchandiseValue = (float) $returnOrders->sum('total_amount');

        // Net Profitability on Realized Orders:
        $deliveredRevenue = (float) $orders->where('status', 'delivered')->sum('total_amount');
        $estimatedCOGS = $deliveredRevenue * 0.40; // 40% COGS benchmark
        $grossProductMargin = max(0, $deliveredRevenue - $estimatedCOGS);
        $netRealizedProfit = max(0, $grossProductMargin - $totalReturnFinancialLoss);
        $netProfitMargin = $deliveredRevenue > 0 ? round(($netRealizedProfit / $deliveredRevenue) * 100, 1) : 0;

        // 15. Order Status Breakdown
        $statusBreakdown = [
            'pending' => $orders->where('status', 'pending')->count(),
            'confirmed' => $orders->where('status', 'confirmed')->count(),
            'shipped' => $orders->where('status', 'shipped')->count(),
            'delivered' => $orders->where('status', 'delivered')->count(),
            'returned' => $returnCount,
            'cancelled' => $orders->where('status', 'cancelled')->count(),
        ];

        return view('admin.analytics.sales_dashboard', compact(
            'range',
            'rangeLabel',
            'customStart',
            'customEnd',
            'grossSales',
            'salesGrowth',
            'netRealizedSales',
            'totalOrdersCount',
            'ordersGrowth',
            'aov',
            'aovGrowth',
            'totalDiscounts',
            'totalShippingCollected',
            'prepaidRevenue',
            'codRevenue',
            'prepaidRatio',
            'codRatio',
            'codTotalCount',
            'codDelivered',
            'codReturned',
            'codRealizationRate',
            'codAov',
            'prepaidTotalCount',
            'prepaidDelivered',
            'prepaidReturned',
            'prepaidRealizationRate',
            'prepaidAov',
            'repeatCustomerRatio',
            'dailyRunRate',
            'forecast30Days',
            'monthlyTarget',
            'targetPacingPercent',
            'chartLabels',
            'chartRevenue',
            'chartOrderCount',
            'hourlyDistribution',
            'peakHourLabel',
            'topProducts',
            'lowStockRisks',
            'topStatesData',
            'topCitiesData',
            'codCustomers',
            'returnCount',
            'overallReturnRate',
            'codReturnCount',
            'codReturnRate',
            'prepaidReturnCount',
            'prepaidReturnRate',
            'returnLogisticsLoss',
            'returnPackagingLoss',
            'totalReturnFinancialLoss',
            'returnedMerchandiseValue',
            'deliveredRevenue',
            'estimatedCOGS',
            'grossProductMargin',
            'netRealizedProfit',
            'netProfitMargin',
            'statusBreakdown'
        ));
    }
}
