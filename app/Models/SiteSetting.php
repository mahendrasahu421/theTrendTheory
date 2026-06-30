<?php
// app/Models/SiteSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    /**
     * Get all settings as array with caching (for homepage)
     */
    public static function getAll()
    {
        return Cache::remember('site_settings_array', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get all settings as collection with caching (for backward compatibility)
     */
    public static function allCached()
    {
        return Cache::remember('site_settings_collection', 3600, function () {
            return self::all();
        });
    }

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $settings = self::getAll();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear all caches
        self::clearCache();

        return $setting;
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::forget('site_settings_array');
        Cache::forget('site_settings_collection');
    }
}