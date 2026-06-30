<?php
// app/Models/Media.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    
    protected $fillable = [
        'model_type',
        'model_id',
        'collection',
        'file_name',
        'file_id',
        'url',
        'thumb_url',
        'width',
        'height',
        'size',
        'mime_type',
        'alt_text',
        'sort_order',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'size' => 'integer'
    ];

    // Get file type (image/video) from mime_type
    public function getFileTypeAttribute()
    {
        if (str_starts_with($this->mime_type, 'video/')) {
            return 'video';
        }
        return 'image';
    }

    // Get title from alt_text
    public function getTitleAttribute()
    {
        return $this->alt_text ?? 'Untitled';
    }

    // FIX: Add getImageUrl method with optional dimensions
    public function getImageUrl($width = null, $height = null)
    {
        if (!$this->url) return null;
        
        if ($width && $height) {
            return $this->url . "?tr=w-{$width},h-{$height},fo-auto,q-80";
        }
        
        return $this->url;
    }

    // Get thumbnail URL
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumb_url) {
            return $this->thumb_url;
        }
        
        if ($this->file_type === 'video') {
            return $this->url . '?tr=iv-1';
        }
        
        return $this->url;
    }

    // Get optimized URL with transformations
    public function getOptimizedUrl($width = null, $height = null)
    {
        if (!$this->url) return null;
        
        if ($this->file_type === 'video') {
            if ($width && $height) {
                return $this->url . "?tr=w-{$width},h-{$height},fo-auto";
            }
            return $this->url;
        }
        
        if ($width && $height) {
            return $this->url . "?tr=w-{$width},h-{$height},fo-auto,q-80";
        }
        
        return $this->url;
    }

    // Scope for gallery items
    public function scopeGallery($query)
    {
        return $query->where('collection', 'gallery')
            ->orWhere('collection', 'video_section');
    }
    
    // Scope for product images
    public function scopeProductImages($query, $productId)
    {
        return $query->where('model_type', 'App\Models\Product')
            ->where('model_id', $productId)
            ->where('collection', 'default');
    }
}