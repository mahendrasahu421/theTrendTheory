<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    protected $table    = 'returns';
    protected $fillable = ['order_id','user_id','return_number','type','status','reason','description','images','exchange_size','exchange_color','admin_notes'];
    protected $casts    = ['images'=>'array'];

    public function order()  { return $this->belongsTo(Order::class); }
    public function user()   { return $this->belongsTo(User::class); }
    public function items()  { return $this->hasMany(ReturnItem::class,'return_id'); }
    public function refund() { return $this->hasOne(Refund::class,'return_id'); }

    protected static function boot() {
        parent::boot();
        static::creating(function($r) {
            if(empty($r->return_number))
                $r->return_number='RTN-'.date('Y').'-'.str_pad(static::whereYear('created_at',date('Y'))->count()+1,5,'0',STR_PAD_LEFT);
        });
    }
    public function getReasonLabelAttribute(): string {
        return match($this->reason) { 'wrong_item'=>'Wrong Item','damaged'=>'Damaged','not_as_described'=>'Not as Described','size_issue'=>'Size Issue','changed_mind'=>'Changed Mind','other'=>'Other',default=>ucfirst($this->reason) };
    }
}
