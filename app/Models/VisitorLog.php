<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'country',
        'country_code',
        'state',
        'city',
        'postal_code',
        'latitude',
        'longitude',
        'source',
        'referrer_url',
        'landing_page',
        'current_url',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'device_type',
        'device_brand',
        'device_model',
        'os',
        'os_version',
        'browser',
        'browser_version',
        'user_agent',
        'page_views_count',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'page_views_count' => 'integer',
        'latitude'         => 'float',
        'longitude'        => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSourceBadgeAttribute(): string
    {
        $src = strtolower($this->source ?? 'direct');
        if (str_contains($src, 'instagram')) return 'badge-instagram';
        if (str_contains($src, 'facebook')) return 'badge-facebook';
        if (str_contains($src, 'google')) return 'badge-google';
        if (str_contains($src, 'youtube')) return 'badge-youtube';
        if (str_contains($src, 'whatsapp')) return 'badge-whatsapp';
        if (str_contains($src, 'direct')) return 'badge-direct';
        return 'badge-referral';
    }

    public function getSourceIconAttribute(): string
    {
        $src = strtolower($this->source ?? 'direct');
        if (str_contains($src, 'instagram')) return 'bi-instagram';
        if (str_contains($src, 'facebook')) return 'bi-facebook';
        if (str_contains($src, 'google')) return 'bi-google';
        if (str_contains($src, 'youtube')) return 'bi-youtube';
        if (str_contains($src, 'whatsapp')) return 'bi-whatsapp';
        if (str_contains($src, 'direct')) return 'bi-box-arrow-in-down-right';
        return 'bi-link-45deg';
    }

    public function getDeviceIconAttribute(): string
    {
        return match (strtolower($this->device_type ?? 'mobile')) {
            'desktop' => 'bi-laptop',
            'tablet'  => 'bi-tablet',
            'bot'     => 'bi-robot',
            default   => 'bi-phone',
        };
    }
}
