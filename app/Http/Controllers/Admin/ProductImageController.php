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

    public function setPrimary(ProductImage $image)
    {
        ProductImage::where('product_id', $image->product_id)
            ->update(['is_primary' => false]);
        
        $image->update(['is_primary' => true]);

        $image->product?->updateQuietly(['image' => $image->url]);

        if ($image->file_id || $image->url) {
            Media::where('model_type', \App\Models\Product::class)
                ->where('model_id', $image->product_id)
                ->update(['is_primary' => false]);

            Media::where('model_type', \App\Models\Product::class)
                ->where('model_id', $image->product_id)
                ->where(function ($query) use ($image) {
                    if ($image->file_id) {
                        $query->where('file_id', $image->file_id);
                    }
                    if ($image->url) {
                        $image->file_id
                            ? $query->orWhere('url', $image->url)
                            : $query->where('url', $image->url);
                    }
                })
                ->update(['is_primary' => true]);
        }
        
        return response()->json(['success' => true]);
    }

    public function destroy(ProductImage $image)
    {
        $product = $image->product;
        $wasMainImage = $product && $product->image === $image->url;

        if ($product && ($image->file_id || $image->url)) {
            Media::where('model_type', \App\Models\Product::class)
                ->where('model_id', $product->id)
                ->where(function ($query) use ($image) {
                    if ($image->file_id) {
                        $query->where('file_id', $image->file_id);
                    }
                    if ($image->url) {
                        $image->file_id
                            ? $query->orWhere('url', $image->url)
                            : $query->where('url', $image->url);
                    }
                })
                ->delete();
        }

        $image->delete();

        if ($wasMainImage && $product) {
            $next = ProductImage::where('product_id', $product->id)
                ->orderByDesc('is_primary')
                ->orderBy('sort_order')
                ->first();

            if ($next) {
                $next->update(['is_primary' => true]);
                $product->updateQuietly(['image' => $next->url]);
            } else {
                $nextMedia = Media::where('model_type', \App\Models\Product::class)
                    ->where('model_id', $product->id)
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order')
                    ->first();

                if ($nextMedia) {
                    $nextMedia->update(['is_primary' => true]);
                }

                $product->updateQuietly(['image' => $nextMedia?->url]);
            }
        }

        return response()->json(['success' => true]);
    }
}
