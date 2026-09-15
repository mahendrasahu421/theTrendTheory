<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'category',
        'summary',
        'content',
        'image_url',
        'image_public_id',
        'author_name',
        'read_time',
        'tags',
        'is_published',
        'is_featured',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * Scope for published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for featured articles.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for blogs.
     */
    public function scopeBlogs($query)
    {
        return $query->where('type', 'blog');
    }

    /**
     * Scope for news.
     */
    public function scopeNews($query)
    {
        return $query->where('type', 'news');
    }

    /**
     * Accessor for tags array.
     */
    public function getTagsListAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_map('trim', explode(',', $this->tags));
    }

    /**
     * Automatically generate unique slug from title.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Increment view count safely.
     */
    public function recordView(): void
    {
        $this->increment('views_count');
    }
}
