<?php
// app/Http/Controllers/Admin/MediaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\ProductImage;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

            $isColorUpload = $modelClass === \App\Models\Product::class && $request->filled('color_id');
            $isPrimary = !$isColorUpload && $request->boolean('is_primary', false);
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
            if ($isColorUpload) {
                $isColorPrimary = $request->boolean('is_primary', false) || !ProductImage::where('product_id', $request->model_id)
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

            if ($modelClass === \App\Models\Product::class) {
                if ($isPrimary) {
                    \App\Models\Product::where('id', $request->model_id)->update(['image' => $media->url]);
                }
                if ($request->collection === 'front_print') {
                    $prod = \App\Models\Product::find($request->model_id);
                    if ($prod) {
                        if (empty($prod->front_image) || $isPrimary) {
                            $prod->updateQuietly(['front_image' => $media->url]);
                        }
                        if (empty($prod->image)) {
                            $prod->updateQuietly(['image' => $media->url]);
                        }
                    }
                } elseif ($request->collection === 'back_print') {
                    $prod = \App\Models\Product::find($request->model_id);
                    if ($prod && (empty($prod->back_image) || $isPrimary)) {
                        $prod->updateQuietly(['back_image' => $media->url]);
                    }
                }
            }

            $imageSource = $productImage ? 'product_image' : 'media';
            $imageId = $productImage?->id ?? $media->id;

            return response()->json([
                'success' => true,
                'media' => [
                    'id' => $imageId,
                    'source' => $imageSource,
                    'source_key' => $imageSource . '_' . $imageId,
                    'source_label' => $productImage ? ($request->alt_text ?: 'Color image') : 'Media',
                    'url' => $media->url,
                    'thumb_url' => $media->thumb_url,
                    'alt_text' => $media->alt_text,
                    'color_id' => $request->color_id,
                    'is_primary' => $productImage ? (bool) $productImage->is_primary : (bool) $media->is_primary,
                    'product_image_id' => $productImage?->id,
                    'primary_url' => $productImage
                        ? route('admin.product-images.primary', $productImage)
                        : route('admin.media.primary', $media),
                    'delete_url' => $productImage
                        ? route('admin.product-images.destroy', $productImage)
                        : route('admin.media.destroy', $media),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Media $media)
    {
        try {
            // Delete from Storage / Cloudinary safely
            if ($media->file_id) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($media->file_id)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($media->file_id);
                } else {
                    $this->cloudinary->delete($media->file_id);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Media file delete error: ' . $e->getMessage());
        }

        if ($media->model_type === \App\Models\Product::class && ($media->file_id || $media->url)) {
            ProductImage::where('product_id', $media->model_id)
                ->where(function ($query) use ($media) {
                    if ($media->file_id) {
                        $query->where('file_id', $media->file_id);
                    }
                    if ($media->url) {
                        $media->file_id
                            ? $query->orWhere('url', $media->url)
                            : $query->where('url', $media->url);
                    }
                })
                ->delete();
        }

        $modelType = $media->model_type;
        $modelId = $media->model_id;
        $wasPrimary = $media->is_primary;

        $media->delete();

        // If the primary image was deleted for a product, elect a new primary image
        if ($wasPrimary && $modelType === \App\Models\Product::class) {
            $nextMedia = Media::where('model_type', $modelType)
                ->where('model_id', $modelId)
                ->orderBy('sort_order')
                ->first();
            if ($nextMedia) {
                $nextMedia->update(['is_primary' => true]);
                \App\Models\Product::where('id', $modelId)->update(['image' => $nextMedia->url]);
            } else {
                $nextProductImage = ProductImage::where('product_id', $modelId)
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order')
                    ->first();

                if ($nextProductImage) {
                    $nextProductImage->update(['is_primary' => true]);
                }

                \App\Models\Product::where('id', $modelId)->update(['image' => $nextProductImage?->url]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
    }

    public function setPrimary(Media $media)
    {
        $isProductMedia = $media->model_type === \App\Models\Product::class;

        if ($media->model_type === 'App\Models\Gallery') {
            Media::where('model_type', 'App\Models\Gallery')
                ->whereIn('collection', ['gallery', 'video_section'])
                ->update(['is_primary' => false]);
        } else {
            Media::where('model_type', $media->model_type)
                ->where('model_id', $media->model_id)
                ->where('collection', $media->collection)
                ->update(['is_primary' => false]);
        }

        $media->update(['is_primary' => true]);

        if ($isProductMedia) {
            \App\Models\Product::where('id', $media->model_id)->update(['image' => $media->url]);

            $productImage = ProductImage::where('product_id', $media->model_id)
                ->where(function ($query) use ($media) {
                    if ($media->file_id) {
                        $query->where('file_id', $media->file_id);
                    }
                    if ($media->url) {
                        $media->file_id
                            ? $query->orWhere('url', $media->url)
                            : $query->where('url', $media->url);
                    }
                })
                ->first();

            if ($productImage) {
                ProductImage::where('product_id', $productImage->product_id)
                    ->update(['is_primary' => false]);
                $productImage->update(['is_primary' => true]);
            }
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
        'subtitle' => 'nullable|string|max:255',
        'button_link' => 'nullable|string|max:1000',
        'product_ids' => 'nullable|array',
        'product_ids.*' => 'integer|exists:products,id',
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
        $isFirstGalleryMedia = !Media::where('model_type', 'App\Models\Gallery')
            ->whereIn('collection', ['gallery', 'video_section'])
            ->exists();
        
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
            'subtitle' => $request->subtitle,
            'button_link' => $this->normalizeGalleryLink($request->button_link, $request->title),
            'sort_order' => $request->sort_order ?? 0,
            'is_primary' => $isFirstGalleryMedia
        ]);
        $this->syncGalleryProducts($media, $request->input('product_ids', []));

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully',
            'media' => [
                'id' => $media->id,
                'title' => $media->alt_text,
                'subtitle' => $media->subtitle,
                'button_link' => $media->button_link,
                'product_ids' => $media->products()->pluck('products.id')->values(),
                'url' => $media->url,
                'thumb_url' => $media->thumb_url,
                'type' => str_starts_with($media->mime_type, 'video/') ? 'video' : 'image',
                'sort_order' => $media->sort_order,
                'is_primary' => $media->is_primary
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
     * Get all gallery media (Blade view or JSON API)
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
        
        $mediaList = $query->with('products')->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // If AJAX / JSON API request, return JSON
        if ($request->expectsJson() || $request->ajax() || $request->is('api/*') || $request->filled('json')) {
            $media = $mediaList->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->alt_text,
                    'subtitle' => $item->subtitle,
                    'button_link' => $item->button_link,
                    'product_ids' => $item->products->pluck('id')->values(),
                    'url' => $item->url,
                    'thumb_url' => $item->thumb_url ?? $item->url,
                    'type' => str_starts_with($item->mime_type, 'video/') ? 'video' : 'image',
                    'mime_type' => $item->mime_type,
                    'sort_order' => $item->sort_order,
                    'is_primary' => $item->is_primary,
                    'created_at' => $item->created_at ? $item->created_at->format('Y-m-d') : null
                ];
            });
            return response()->json($media);
        }

        // Otherwise return full Admin Blade view
        $galleryItems = $mediaList;
        $products = \App\Models\Product::where('is_active', true)->select('id', 'name', 'slug', 'price')->orderBy('name')->get();
        $stats = [
            'total' => $galleryItems->count(),
            'images_count' => $galleryItems->filter(fn($m) => !str_starts_with($m->mime_type, 'video/'))->count(),
            'videos_count' => $galleryItems->filter(fn($m) => str_starts_with($m->mime_type, 'video/'))->count(),
            'hero_video' => $galleryItems->firstWhere('is_primary', true),
        ];

        return view('admin.media.gallery', compact('galleryItems', 'products', 'stats'));
    }

    /**
     * Update gallery media
     */
    public function updateGallery(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:1000',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id',
            'sort_order' => 'nullable|integer'
        ]);

        $media = Media::findOrFail($id);
        
        // Verify it's gallery media
        if ($media->model_type !== 'App\Models\Gallery') {
            return response()->json(['success' => false, 'message' => 'Invalid media type'], 400);
        }

        $media->update([
            'alt_text' => $request->title,
            'subtitle' => $request->subtitle,
            'button_link' => $this->normalizeGalleryLink($request->button_link, $request->title),
            'sort_order' => $request->sort_order ?? $media->sort_order
        ]);
        $this->syncGalleryProducts($media, $request->input('product_ids', []));

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'media' => [
                'id' => $media->id,
                'title' => $media->alt_text,
                'subtitle' => $media->subtitle,
                'button_link' => $media->button_link,
                'product_ids' => $media->products()->pluck('products.id')->values(),
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

        $media->products()->sync([]);
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
            'subtitle' => $media->subtitle,
            'button_link' => $media->button_link,
            'product_ids' => $media->products()->pluck('products.id')->values(),
            'sort_order' => $media->sort_order,
            'url' => $media->url,
            'thumb_url' => $media->thumb_url,
            'mime_type' => $media->mime_type,
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
                'title' => $video->alt_text,
                'subtitle' => $video->subtitle,
                'button_link' => $video->button_link
            ]);
        }
        
        return response()->json(null);
    }

    private function normalizeGalleryLink(?string $link, string $title): string
    {
        $link = trim((string) $link);

        if ($link !== '') {
            if (filter_var($link, FILTER_VALIDATE_URL)) {
                return $link;
            }

            $link = ltrim($link, '/');

            if (str_starts_with($link, 'collection/') || str_starts_with($link, 'collections/') || str_starts_with($link, 'shop/')) {
                return '/' . $link;
            }

            if (str_starts_with($link, 'collection') || str_starts_with($link, 'collections') || $link === 'shop') {
                return '/' . $link;
            }

            return '/collection/' . Str::slug($link);
        }

        $slug = Str::slug($title);
        $slug = preg_replace('/-(collection|collections)$/', '', $slug);

        return '/collection/' . ($slug ?: 'shop');
    }

    private function syncGalleryProducts(Media $media, array $productIds): void
    {
        $syncData = [];

        foreach (array_values(array_unique(array_filter($productIds))) as $index => $productId) {
            $syncData[(int) $productId] = ['sort_order' => $index];
        }

        $media->products()->sync($syncData);
    }
}
