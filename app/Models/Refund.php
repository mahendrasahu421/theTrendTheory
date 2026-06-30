<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = ['return_id','order_id','user_id','amount','method','status','refund_id','bank_name','account_number','ifsc_code','processed_at','notes'];
    protected $casts    = ['amount'=>'decimal:2','processed_at'=>'datetime'];

    public function order()       { return $this->belongsTo(Order::class); }
    public function user()        { return $this->belongsTo(User::class); }
    public function orderReturn() { return $this->belongsTo(OrderReturn::class,'return_id'); }
    public function getStatusBadgeAttribute(): string {
        return match($this->status) { 'completed'=>'badge-success','processing'=>'badge-info','pending'=>'badge-warning','failed'=>'badge-danger',default=>'badge-gray' };
    }
}
