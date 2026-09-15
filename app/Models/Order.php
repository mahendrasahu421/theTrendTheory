<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'status',
        'subtotal', 'discount_amount', 'shipping_charge', 'total_amount',
        'coupon_code', 'notes', 'payment_status',
        // Payment
        'payment_method', 'payment_id', 'razorpay_order_id',
        // Shipping address
        'shipping_name', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_state', 'shipping_pincode',
        'tracking_number', 'courier_name',
    ];
    protected $casts = ['subtotal'=>'decimal:2','total_amount'=>'decimal:2','discount_amount'=>'decimal:2','shipping_charge'=>'decimal:2'];

    public function user()     { return $this->belongsTo(User::class); }
    public function items()    { return $this->hasMany(OrderItem::class); }
    public function address()  { return $this->hasOne(OrderAddress::class)->where('type','shipping'); }
    public function payment()  { return $this->hasOne(Payment::class); }
    public function shipment() { return $this->hasOne(Shipment::class); }
    public function return()   { return $this->hasOne(OrderReturn::class); }
    public function couponUsage(){ return $this->hasOne(CouponUsage::class); }

    protected static function boot() {
        parent::boot();
        static::creating(function($o) {
            if(empty($o->order_number))
                $o->order_number='TTT-'.date('Y').'-'.str_pad(static::whereYear('created_at',date('Y'))->count()+1,6,'0',STR_PAD_LEFT);
        });
    }

    public function getStatusBadgeAttribute(): string {
        return match($this->status) { 'pending'=>'badge-warning','confirmed'=>'badge-success','cancelled'=>'badge-danger',default=>'badge-gray' };
    }

    public function setShippingNameAttribute($value) {
        $this->attributes['shipping_name'] = !empty($value) ? mb_convert_case(trim($value), MB_CASE_TITLE, "UTF-8") : $value;
    }
}
