<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingSetting;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $shipping = ShippingSetting::instance();
        return view('admin.shipping.index', compact('shipping'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'flat_rate'           => 'required|numeric|min:0',
            'free_shipping_above' => 'required|numeric|min:0',
            'cod_charges'         => 'required|numeric|min:0',
            'estimated_days_min'  => 'required|integer|min:1',
            'estimated_days_max'  => 'required|integer|min:1',
        ]);

        ShippingSetting::instance()->update([
            'flat_rate'           => $request->flat_rate,
            'free_shipping_above' => $request->free_shipping_above,
            'cod_enabled'         => $request->boolean('cod_enabled'),
            'cod_charges'         => $request->cod_charges,
            'estimated_days_min'  => $request->estimated_days_min,
            'estimated_days_max'  => $request->estimated_days_max,
        ]);

        return back()->with('success', 'Shipping settings updated!');
    }
}