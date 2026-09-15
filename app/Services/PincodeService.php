<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\PincodeRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PincodeService
{
    /**
     * Metro Pincode prefixes for quick zone identification in India.
     */
    protected array $metroPrefixes = [
        '11' => 'Delhi NCR',
        '12' => 'Delhi NCR / Haryana',
        '20' => 'UP NCR / Noida',
        '40' => 'Mumbai Metro',
        '41' => 'Pune Metro',
        '56' => 'Bengaluru Metro',
        '50' => 'Hyderabad Metro',
        '60' => 'Chennai Metro',
        '70' => 'Kolkata Metro',
        '38' => 'Ahmedabad',
    ];

    /**
     * Check pincode eligibility, speed, delivery date, COD and return rules.
     */
    public function checkPincode(string $pincode): array
    {
        $cleanPin = trim($pincode);

        if (strlen($cleanPin) !== 6 || !ctype_digit($cleanPin)) {
            return [
                'success' => false,
                'is_serviceable' => false,
                'message' => 'Please enter a valid 6-digit Indian pincode.',
            ];
        }

        // 1. Check if custom rule exists in DB
        $rule = PincodeRule::where('pincode', $cleanPin)->first();

        // If not in DB, resolve zone and defaults
        if (!$rule) {
            $prefix = substr($cleanPin, 0, 2);
            $isMetro = isset($this->metroPrefixes[$prefix]);
            $zone = $isMetro ? 'Metro' : 'Standard';
            $daysMin = $isMetro ? 2 : 4;
            $daysMax = $isMetro ? 4 : 6;
            $isCodAllowed = true;
            $isReturnAllowed = true;
            $isExchangeOnly = false;
            $riskLevel = 'low';
            $city = null;
            $state = null;
        } else {
            $zone = $rule->zone ?: 'Standard';
            $daysMin = $rule->delivery_days_min ?: 3;
            $daysMax = $rule->delivery_days_max ?: 5;
            $isCodAllowed = (bool) $rule->is_cod_allowed;
            $isReturnAllowed = (bool) $rule->is_return_allowed;
            $isExchangeOnly = (bool) $rule->is_exchange_only;
            $riskLevel = $rule->risk_level ?: 'low';
            $city = $rule->city;
            $state = $rule->state;

            // If marked exchange only, return is restricted
            if ($isExchangeOnly) {
                $isReturnAllowed = false;
            }
        }

        // Calculate delivery date range
        $today = Carbon::now();
        $dateFrom = $today->copy()->addDays($daysMin);
        $dateTo = $today->copy()->addDays($daysMax);

        // Format: "Friday, 5 Sep - Sunday, 7 Sep"
        $estimatedDeliveryDate = $dateFrom->format('D, d M') . ' - ' . $dateTo->format('D, d M');

        return [
            'success' => true,
            'pincode' => $cleanPin,
            'city' => $city,
            'state' => $state,
            'zone' => $zone,
            'delivery_days_min' => $daysMin,
            'delivery_days_max' => $daysMax,
            'delivery_days_text' => "{$daysMin}-{$daysMax} Business Days",
            'estimated_delivery_date' => $estimatedDeliveryDate,
            'is_serviceable' => $rule ? (bool)$rule->is_serviceable : true,
            'is_cod_allowed' => $isCodAllowed,
            'is_return_allowed' => $isReturnAllowed,
            'is_exchange_only' => $isExchangeOnly,
            'risk_level' => $riskLevel,
            'policy_summary' => $isExchangeOnly
                ? 'Exchange Only (Returns / Cash Refunds not supported for this pincode)'
                : '7 Days Free Returns & Exchanges Available',
            'cod_message' => $isCodAllowed
                ? 'Cash on Delivery & Prepaid Available'
                : 'COD Unavailable (Prepaid Only due to high transit cancellations in this region)',
        ];
    }

    /**
     * Run Auto-Analysis across real orders and returns data.
     * Flags pincodes with high COD cancellation rate / high return rate.
     */
    public function autoAnalyzeRisk(): array
    {
        $orderStats = Order::select(
            'shipping_pincode',
            DB::raw('COUNT(*) as total_orders'),
            DB::raw("SUM(CASE WHEN LOWER(payment_method) = 'cod' THEN 1 ELSE 0 END) as cod_orders"),
            DB::raw("SUM(CASE WHEN LOWER(status) IN ('cancelled', 'failed', 'rto') THEN 1 ELSE 0 END) as rto_orders")
        )
        ->whereNotNull('shipping_pincode')
        ->where('shipping_pincode', '!=', '')
        ->groupBy('shipping_pincode')
        ->get();

        $returnStats = OrderReturn::join('orders', 'returns.order_id', '=', 'orders.id')
            ->select('orders.shipping_pincode', DB::raw('COUNT(*) as total_returns'))
            ->whereNotNull('orders.shipping_pincode')
            ->groupBy('orders.shipping_pincode')
            ->pluck('total_returns', 'shipping_pincode');

        $updatedCount = 0;
        $flaggedCodCount = 0;
        $flaggedExchangeCount = 0;

        foreach ($orderStats as $stat) {
            $pin = trim($stat->shipping_pincode);
            if (strlen($pin) !== 6 || !ctype_digit($pin)) continue;

            $total = (int) $stat->total_orders;
            $cod = (int) $stat->cod_orders;
            $rto = (int) $stat->rto_orders;
            $returns = (int) ($returnStats[$pin] ?? 0);

            $returnRate = $total > 0 ? round(($returns / $total) * 100, 2) : 0;
            $rtoRate = $total > 0 ? round(($rto / $total) * 100, 2) : 0;

            // Risk assessment logic:
            // 1. High COD RTO: If COD >= 2 and RTO rate > 35%, disable COD.
            // 2. High Return Rate: If total orders >= 2 and Return Rate > 35%, force Exchange Only.
            $isCodAllowed = true;
            $isExchangeOnly = false;
            $riskLevel = 'low';

            if ($rtoRate >= 35 && $cod >= 2) {
                $isCodAllowed = false;
                $riskLevel = 'high';
                $flaggedCodCount++;
            }

            if ($returnRate >= 35 && $total >= 2) {
                $isExchangeOnly = true;
                $riskLevel = 'high';
                $flaggedExchangeCount++;
            } elseif ($returnRate >= 20 || $rtoRate >= 20) {
                $riskLevel = 'medium';
            }

            PincodeRule::updateOrCreate(
                ['pincode' => $pin],
                [
                    'total_orders' => $total,
                    'cod_orders' => $cod,
                    'returned_orders' => $returns,
                    'rto_orders' => $rto,
                    'return_rate' => $returnRate,
                    'rto_rate' => $rtoRate,
                    'is_cod_allowed' => $isCodAllowed,
                    'is_exchange_only' => $isExchangeOnly,
                    'is_return_allowed' => !$isExchangeOnly,
                    'risk_level' => $riskLevel,
                ]
            );

            $updatedCount++;
        }

        return [
            'success' => true,
            'total_analyzed' => $updatedCount,
            'cod_restricted' => $flaggedCodCount,
            'exchange_only' => $flaggedExchangeCount,
        ];
    }
}