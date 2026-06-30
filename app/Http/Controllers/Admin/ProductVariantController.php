<?php
// app/Http/Controllers/Admin/ProductVariantController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'sku' => 'nullable|string|unique:product_variants,sku',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $variant = ProductVariant::create($validated);
        
        return response()->json(['success' => true, 'variant' => $variant]);
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'sku' => 'nullable|string|unique:product_variants,sku,' . $variant->id,
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $variant->update($validated);
        
        return response()->json(['success' => true]);
    }

    public function destroy(ProductVariant $variant)
    {
        $variant->delete();
        return response()->json(['success' => true]);
    }
}