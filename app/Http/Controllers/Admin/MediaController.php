<?php
// app/Http/Controllers/Admin/MediaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\ProductImage;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class MediaController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    // ========== EXISTING METHODS (Product/Category Media) ==========
    
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120',
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'collection' => 'nullable|string',
            'alt_text' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
            'color_id' => 'nullable|exists:colors,id',
        ]);

        try {
            $modelClass = match($request->model_type) {
                'product' => \App\Models\Product::class,
                'category' => \App\Models\Category::class,
                default => \App\Models\Product::class,
            };

            $upload = $this->cloudinary->upload(
                $request->file('file'),
                'products'
            );

            $isPrimary = $request->boolean('is_primary', false);
            if ($isPrimary) {
                Media::where('model_type', $modelClass)
                    ->where('model_id', $request->model_id)
                    ->where('collection', $request->collection ?? 'default')
                    ->update(['is_primary' => false]);
            }

            $media = Media::create([
                'model_type' => $modelClass,
                'model_id' => $request->model_id,
                'collection' => $request->collection ?? 'default',
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_id' => $upload['public_id'] ?? $upload['fileId'] ?? null,
                'url' => $upload['url'],
                'thumb_url' => $upload['url'],
                'size' => $request->file('file')->getSize(),
                'mime_type' => $request->file('file')->getMimeType(),
                'alt_text' => $request->alt_text,
                'is_primary' => $isPrimary,
                'sort_order' => Media::where('model_type', $modelClass)->where('model_id', $request->model_id)->count(),
            ]);

            $productImage = null;
            if ($modelClass === \App\Models\Product::class && $request->filled('color_id')) {
                $isColorPrimary = $isPrimary || !ProductImage::where('product_id', $request->model_id)
                    ->where('color_id', $request->color_id)
                    ->exists();

                if ($isColorPrimary) {
                    ProductImage::where('product_id', $request->model_id)
                        ->where('color_id', $request->color_id)
                        ->update(['is_primary' => false]);
                }

                $productImage = ProductImage::create([
                    'product_id' => $request->model_id,
                    'color_id' => $request->color_id,
                    'file_id' => $media->file_id,
                    'url' => $media->url,
                    'alt_text' => $media->alt_text,
                    'is_primary' => $isColorPrimary,
                    'sort_order' => ProductImage::where('product_id', $request->model_id)
                        ->where('color_id', $request->color_id)
                        ->count(),
                ]);
            }

            return response()->json([
                'success' => true,
                'media' => [
                    'id' => $media->id,
                    'url' => $media->url,
                    'thumb_url' => $media->thumb_url,
                    'is_primary' => $media->is_primary,
                    'product_image_id' => $productImage?->id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Media $media)
    {
        // Delete from Cloudinary
        if ($media->file_id) {
            $this->cloudinary->delete($media->file_id);
        }
        ProductImage::where('file_id', $media->file_id)
            ->orWhere('url', $media->url)
            ->delete();
        $media->delete();
        return response()->json(['success' => true]);
    }

    public function setPrimary(Media $media)
    {
        Media::where('model_type', $media->model_type)
            ->where('model_id', $media->model_id)
            ->where('collection', $media->collection)
            ->update(['is_primary' => false]);
        $media->update(['is_primary' => true]);

        $productImage = ProductImage::where('file_id', $media->file_id)
            ->orWhere('url', $media->url)
            ->first();
        if ($productImage) {
            ProductImage::where('product_id', $productImage->product_id)
                ->where('color_id', $productImage->color_id)
                ->update(['is_primary' => false]);
            $productImage->update(['is_primary' => true]);
        }

        return response()->json(['success' => true]);
    }

    // ========== NEW METHODS FOR GALLERY (Video/Image Upload) ==========

    /**
     * Upload media to gallery (images and videos)
     */
  public function uploadGallery(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'file' => 'required|file|max:51200',
        'sort_order' => 'nullable|integer',
        'section' => 'nullable|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false, 
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $file = $request->file('file');
        $collection = $request->section ?? 'gallery';
        
        // Determine folder based on file type
        $folder = str_starts_with($file->getMimeType(), 'video/') ? 'gallery/videos' : 'gallery/images';
        
        // Upload to Cloudinary
        $upload = $this->cloudinary->upload($file, $folder);
        
        // Check if upload was successful
        if (!isset($upload['url'])) {
            return response()->json([
                'success' => false, 
                'message' => 'Upload failed'
            ], 500);
        }

        // Get dimensions for images
        $width = null;
        $height = null;
        if (str_starts_with($file->getMimeType(), 'image/')) {
            $imageInfo = getimagesize($file->getRealPath());
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
            }
        }

        // Save to database
        $media = Media::create([
            'model_type' => 'App\Models\Gallery',
            'model_id' => 0,
            'collection' => $collection,
            'file_name' => $file->getClientOriginalName(),
            'file_id' => $upload['public_id'] ?? $upload['fileId'] ?? null,
            'url' => $upload['url'],
            'thumb_url' => $upload['thumbnailUrl'] ?? ($upload['url'] ?? null),
            'width' => $width,
            'height' => $height,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'alt_text' => $request->title,
            'sort_order' => $request->sort_order ?? 0,
            'is_primary' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully',
            'media' => [
                'id' => $media->id,
                'title' => $media->alt_text,
                'url' => $media->url,
                'thumb_url' => $media->thumb_url,
                'type' => str_starts_with($media->mime_type, 'video/') ? 'video' : 'image',
                'sort_order' => $media->sort_order
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Gallery upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false, 
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Get all gallery media
     */
    public function getGalleryMedia(Request $request)
    {
        $query = Media::where('model_type', 'App\Models\Gallery')
            ->whereIn('collection', ['gallery', 'video_section']);
        
        // Filter by type
        if ($request->type === 'image') {
            $query->where('mime_type', 'not like', 'video/%');
        } elseif ($request->type === 'video') {
            $query->where('mime_type', 'like', 'video/%');
        }
        
        $media = $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->alt_text,
                    'url' => $item->url,
                    'thumb_url' => $item->thumb_url ?? $item->url,
                    'type' => str_starts_with($item->mime_type, 'video/') ? 'video' : 'image',
                    'mime_type' => $item->mime_type,
                    'sort_order' => $item->sort_order,
                    'created_at' => $item->created_at ? $item->created_at->format('Y-m-d') : null
                ];
            });
        
        return response()->json($media);
    }

    /**
     * Update gallery media
     */
    public function updateGallery(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sort_order' => 'nullable|integer'
        ]);

        $media = Media::findOrFail($id);
        
        // Verify it's gallery media
        if ($media->model_type !== 'App\Models\Gallery') {
            return response()->json(['success' => false, 'message' => 'Invalid media type'], 400);
        }

        $media->update([
            'alt_text' => $request->title,
            'sort_order' => $request->sort_order ?? $media->sort_order
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'media' => [
                'id' => $media->id,
                'title' => $media->alt_text,
                'sort_order' => $media->sort_order
            ]
        ]);
    }

    /**
     * Delete gallery media
     */
    public function destroyGallery($id)
    {
        $media = Media::findOrFail($id);
        
        // Verify it's gallery media
        if ($media->model_type !== 'App\Models\Gallery') {
            return response()->json(['success' => false, 'message' => 'Invalid media type'], 400);
        }
        
        // Delete from Cloudinary
        if ($media->file_id) {
            $this->cloudinary->delete($media->file_id);
        }
        
        $media->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully'
        ]);
    }

    /**
     * Reorder gallery media
     */
    public function reorderGallery(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:media,id',
            'items.*.sort_order' => 'required|integer'
        ]);

        foreach ($request->items as $item) {
            Media::where('id', $item['id'])
                ->where('model_type', 'App\Models\Gallery')
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully'
        ]);
    }

    /**
     * Get single media for editing
     */
    public function getGalleryMediaItem($id)
    {
        $media = Media::findOrFail($id);
        
        if ($media->model_type !== 'App\Models\Gallery') {
            return response()->json(['success' => false, 'message' => 'Invalid media type'], 400);
        }
        
        return response()->json([
            'id' => $media->id,
            'title' => $media->alt_text,
            'sort_order' => $media->sort_order,
            'url' => $media->url,
            'thumb_url' => $media->thumb_url,
            'type' => str_starts_with($media->mime_type, 'video/') ? 'video' : 'image'
        ]);
    }

    /**
     * Get hero video for frontend
     */
    public function getHeroVideo()
    {
        $video = Media::where('model_type', 'App\Models\Gallery')
            ->where('collection', 'gallery')
            ->where('mime_type', 'like', 'video/%')
            ->orderBy('sort_order')
            ->first();
        
        if ($video) {
            return response()->json([
                'url' => $video->url,
                'thumbnail' => $video->thumb_url ?? $video->url,
                'title' => $video->alt_text
            ]);
        }
        
        return response()->json(null);
    }
}
