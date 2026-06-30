<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $table    = 'order_addresses';
    protected $fillable = ['order_id','type','name','phone','address_line','city','state','pincode'];

    public function order() { return $this->belongsTo(Order::class); }
    public function getFullAddressAttribute(): string { return "{$this->address_line}, {$this->city}, {$this->state} - {$this->pincode}"; }
}
