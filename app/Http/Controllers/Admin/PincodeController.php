<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PincodeRule;
use App\Services\PincodeService;
use Illuminate\Http\Request;

class PincodeController extends Controller
{
    protected PincodeService $pincodeService;

    public function __construct(PincodeService $pincodeService)
    {
        $this->pincodeService = $pincodeService;
    }

    /**
     * Admin Pincodes Management Dashboard
     * GET /admin/pincodes
     */
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));
        $risk = $request->get('risk', '');
        $cod = $request->get('cod', '');
        $exchange = $request->get('exchange', '');
        $zone = $request->get('zone', '');

        $query = PincodeRule::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pincode', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%");
            });
        }

        if ($risk) {
            $query->where('risk_level', $risk);
        }

        if ($cod !== '') {
            $query->where('is_cod_allowed', $cod === '1');
        }

        if ($exchange !== '') {
            $query->where('is_exchange_only', $exchange === '1');
        }

        if ($zone) {
            $query->where('zone', $zone);
        }

        $pincodes = $query->latest()->paginate(25)->withQueryString();

        // High level KPI stats
        $totalTracked = PincodeRule::count();
        $codBlockedCount = PincodeRule::where('is_cod_allowed', false)->count();
        $exchangeOnlyCount = PincodeRule::where('is_exchange_only', true)->count();
        $highRiskCount = PincodeRule::where('risk_level', 'high')->count();

        return view('admin.pincodes.index', compact(
            'pincodes',
            'totalTracked',
            'codBlockedCount',
            'exchangeOnlyCount',
            'highRiskCount',
            'search',
            'risk',
            'cod',
            'exchange',
            'zone'
        ));
    }

    /**
     * Save or update pincode rule
     * POST /admin/pincodes
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pincode'           => 'required|string|size:6|regex:/^\d{6}$/',
            'city'              => 'nullable|string|max:100',
            'state'             => 'nullable|string|max:100',
            'zone'              => 'required|string|max:50',
            'delivery_days_min' => 'required|integer|min:1|max:30',
            'delivery_days_max' => 'required|integer|min:1|max:30|gte:delivery_days_min',
            'is_serviceable'    => 'nullable|boolean',
            'is_cod_allowed'    => 'nullable|boolean',
            'is_exchange_only'  => 'nullable|boolean',
            'risk_level'        => 'required|in:low,medium,high',
            'admin_notes'       => 'nullable|string|max:500',
        ]);

        $isExchangeOnly = $request->boolean('is_exchange_only');

        PincodeRule::updateOrCreate(
            ['pincode' => $validated['pincode']],
            [
                'city'              => $validated['city'] ?? null,
                'state'             => $validated['state'] ?? null,
                'zone'              => $validated['zone'],
                'delivery_days_min' => $validated['delivery_days_min'],
                'delivery_days_max' => $validated['delivery_days_max'],
                'is_serviceable'    => $request->boolean('is_serviceable', true),
                'is_cod_allowed'    => $request->boolean('is_cod_allowed', true),
                'is_exchange_only'  => $isExchangeOnly,
                'is_return_allowed' => !$isExchangeOnly,
                'risk_level'        => $validated['risk_level'],
                'admin_notes'       => $validated['admin_notes'] ?? null,
            ]
        );

        return redirect()->route('admin.pincodes.index')
            ->with('success', "Pincode #{$validated['pincode']} rule saved successfully.");
    }

    /**
     * Fast Toggle COD for Pincode
     * PATCH /admin/pincodes/{pincodeRule}/toggle-cod
     */
    public function toggleCod(PincodeRule $pincodeRule)
    {
        $pincodeRule->is_cod_allowed = !$pincodeRule->is_cod_allowed;
        $pincodeRule->save();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_cod_allowed' => $pincodeRule->is_cod_allowed,
                'message' => 'COD availability updated.'
            ]);
        }

        return back()->with('success', "COD status for pincode {$pincodeRule->pincode} updated.");
    }

    /**
     * Fast Toggle Exchange Only for Pincode
     * PATCH /admin/pincodes/{pincodeRule}/toggle-exchange
     */
    public function toggleExchange(PincodeRule $pincodeRule)
    {
        $pincodeRule->is_exchange_only = !$pincodeRule->is_exchange_only;
        $pincodeRule->is_return_allowed = !$pincodeRule->is_exchange_only;
        $pincodeRule->save();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_exchange_only' => $pincodeRule->is_exchange_only,
                'message' => 'Exchange-only policy updated.'
            ]);
        }

        return back()->with('success', "Exchange-only policy for pincode {$pincodeRule->pincode} updated.");
    }

    /**
     * Run Auto Analysis across all orders
     * POST /admin/pincodes/auto-analyze
     */
    public function autoAnalyze()
    {
        $result = $this->pincodeService->autoAnalyzeRisk();

        return redirect()->route('admin.pincodes.index')
            ->with('success', "Auto-Analysis Completed! Analyzed {$result['total_analyzed']} pincodes. COD Restricted: {$result['cod_restricted']}, Exchange-Only: {$result['exchange_only']}.");
    }

    /**
     * Delete custom rule
     * DELETE /admin/pincodes/{pincodeRule}
     */
    public function destroy(PincodeRule $pincodeRule)
    {
        $pin = $pincodeRule->pincode;
        $pincodeRule->delete();

        return redirect()->route('admin.pincodes.index')
            ->with('success', "Pincode rule for {$pin} removed. Default zone settings will apply.");
    }
}