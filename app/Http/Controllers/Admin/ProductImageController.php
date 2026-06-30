<?php
// app/Http/Controllers/Admin/ProductImageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'nullable|exists:colors,id',
            'media_id' => 'required|exists:media,id',
            'is_primary' => 'boolean',
        ]);

        $media = Media::findOrFail($validated['media_id']);

        // If this is primary, remove primary from others
        if ($validated['is_primary'] ?? false) {
            ProductImage::where('product_id', $validated['product_id'])
                ->where('color_id', $validated['color_id'])
                ->update(['is_primary' => false]);
        }

        $productImage = ProductImage::create([
            'product_id' => $validated['product_id'],
            'color_id' => $validated['color_id'] ?? null,
            'file_id' => $media->file_id,
            'url' => $media->url,
            'alt_text' => $media->alt_text,
            'is_primary' => $validated['is_primary'] ?? false,
        ]);
        
        return response()->json(['success' => true, 'image' => $productImage]);
    }

    public function setPrimary(ProductImage $productImage)
    {
        // Remove primary from other images of same product and color
        ProductImage::where('product_id', $productImage->product_id)
            ->where('color_id', $productImage->color_id)
            ->update(['is_primary' => false]);
        
        $productImage->update(['is_primary' => true]);
        
        return response()->json(['success' => true]);
    }

    public function destroy(ProductImage $productImage)
    {
        $productImage->delete();
        return response()->json(['success' => true]);
    }
}
