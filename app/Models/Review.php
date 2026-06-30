<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'reviewer_name',
        'reviewer_image_url',
        'rating',
        'title',
        'comment',
        'product_tag',
        'likes',
        'review_media',
        'is_verified',
        'is_approved',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'review_media' => 'array',
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }

    public function getReviewerImageUrlAttribute(): string {
        return $this->attributes['reviewer_image_url'] ?? 'https://ui-avatars.com/api/?name='.urlencode($this->reviewer_name ?? 'Customer').'&background=00285a&color=fff&size=80';
    }
    public static function featured(int $limit=6) { return static::where('is_featured',true)->where('is_active',true)->latest()->limit($limit)->get(); }
}
