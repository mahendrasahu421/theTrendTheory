<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getBool(string $key, bool $default = true): bool
    {
        $val = static::get($key, $default ? '1' : '0');
        return in_array($val, ['1', 'true', true, 1], true);
    }
}
