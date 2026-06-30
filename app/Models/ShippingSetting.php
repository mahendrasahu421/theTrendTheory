<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
    protected $fillable = [
        'flat_rate', 'free_shipping_above', 'cod_enabled',
        'cod_charges', 'estimated_days_min', 'estimated_days_max'
    ];
    protected $casts = ['cod_enabled' => 'boolean'];

    // Singleton — hamesha pehli row
    public static function instance(): self
    {
        return static::firstOrCreate([], [
            'flat_rate'           => 99,
            'free_shipping_above' => 999,
            'cod_enabled'         => true,
            'cod_charges'         => 49,
            'estimated_days_min'  => 3,
            'estimated_days_max'  => 7,
        ]);
    }
}