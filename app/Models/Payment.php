<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id','method','status','amount','payment_id','gateway_order_id','gateway_response','paid_at'];
    protected $casts    = ['amount'=>'decimal:2','paid_at'=>'datetime'];

    public function order() { return $this->belongsTo(Order::class); }
    public function isPaid(): bool { return $this->status === 'paid'; }
    public function getStatusBadgeAttribute(): string {
        return match($this->status) { 'paid'=>'badge-success','pending'=>'badge-warning','failed'=>'badge-danger','refunded'=>'badge-purple',default=>'badge-gray' };
    }
}
