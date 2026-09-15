<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PincodeRule extends Model
{
    use HasFactory;

    protected $table = 'pincode_rules';

    protected $fillable = [
        'pincode',
        'city',
        'state',
        'zone',
        'delivery_days_min',
        'delivery_days_max',
        'is_serviceable',
        'is_cod_allowed',
        'is_return_allowed',
        'is_exchange_only',
        'risk_level',
        'total_orders',
        'cod_orders',
        'returned_orders',
        'rto_orders',
        'return_rate',
        'rto_rate',
        'admin_notes',
    ];

    protected $casts = [
        'delivery_days_min' => 'integer',
        'delivery_days_max' => 'integer',
        'is_serviceable'     => 'boolean',
        'is_cod_allowed'     => 'boolean',
        'is_return_allowed'  => 'boolean',
        'is_exchange_only'   => 'boolean',
        'total_orders'       => 'integer',
        'cod_orders'         => 'integer',
        'returned_orders'    => 'integer',
        'rto_orders'         => 'integer',
        'return_rate'        => 'float',
        'rto_rate'           => 'float',
    ];
}

