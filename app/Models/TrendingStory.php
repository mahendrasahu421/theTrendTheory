<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TrendingStory extends Model
{
    protected $fillable = ['title','caption','caption_highlight','image','user_name','user_avatar','badge','badge_type','likes_count','sort_order','is_active'];
    protected $casts    = ['is_active'=>'boolean'];

    public function getImageUrlAttribute(): ?string { return $this->image; }
    public function getUsernameAttribute(): ?string { return $this->user_name; }
    public function getUserAvatarUrlAttribute(): ?string { return $this->user_avatar; }
    public function getBadgeTextAttribute(): ?string { return $this->badge; }
    public function getDurationAttribute(): string { return 'New'; }
    public function getLikesAttribute(): int { return (int) ($this->likes_count ?? 0); }
    public function getViewsAttribute(): int { return 0; }

    public function getFormattedLikesAttribute(): string { $l=$this->likes_count??0; return $l>=1000?round($l/1000,1).'k':(string)$l; }
    public static function active(int $limit=3) { return static::where('is_active',true)->orderBy('sort_order')->limit($limit)->get(); }
}
