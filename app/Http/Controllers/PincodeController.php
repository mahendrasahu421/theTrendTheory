<?php

namespace App\Http\Controllers;

use App\Services\PincodeService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\RateLimiter;

class PincodeController extends Controller
{
    protected PincodeService $pincodeService;

    public function __construct(PincodeService $pincodeService)
    {
        $this->pincodeService = $pincodeService;
    }

    /**
     * Real-time Pincode Checker endpoint
     * GET /api/pincode/check?pincode=110001
     */
    public function check(Request $request)
    {
        $throttleKey = 'pincode_check|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 60)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again shortly.',
            ], 429);
        }
        RateLimiter::hit($throttleKey, 60);

        $pincode = preg_replace('/\D+/', '', (string) $request->get('pincode', ''));
        $result = $this->pincodeService->checkPincode($pincode);

        return response()->json($result);
    }
}