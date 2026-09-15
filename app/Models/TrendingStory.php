<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrendingStory extends Model
{
    protected $fillable = [
        'title',
        'caption',
        'caption_highlight',
        'image',
        'user_name',
        'user_avatar',
        'badge',
        'badge_type',
        'likes_count',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'likes_count' => 'integer',
        'sort_order' => 'integer',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return $this->attributes['image'] ?? null;
    }

    public function getUsernameAttribute(): ?string
    {
        return $this->attributes['user_name'] ?? '@thetrendtheory';
    }

    public function getUserAvatarUrlAttribute(): ?string
    {
        if (!empty($this->attributes['user_avatar'])) {
            return $this->attributes['user_avatar'];
        }
        $name = $this->attributes['user_name'] ?? 'Creator';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=00285a&color=fff&size=80';
    }

    public function getBadgeTextAttribute(): ?string
    {
        return $this->attributes['badge'] ?? 'TRENDING';
    }

    public function getDurationAttribute(): string
    {
        return '0:30';
    }

    public function getLikesAttribute(): int
    {
        return (int) ($this->attributes['likes_count'] ?? 0);
    }

    public function getViewsAttribute(): int
    {
        $likes = (int) ($this->attributes['likes_count'] ?? 0);
        return $likes > 0 ? $likes * 3 : 1200;
    }

    public function getFormattedLikesAttribute(): string
    {
        $l = (int) ($this->attributes['likes_count'] ?? 0);
        return $l >= 1000 ? round($l / 1000, 1) . 'k' : (string) $l;
    }

    public static function active(int $limit = 3)
    {
        return static::where('is_active', true)->orderBy('sort_order')->limit($limit)->get();
    }
}

