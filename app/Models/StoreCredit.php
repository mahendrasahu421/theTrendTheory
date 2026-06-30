<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StoreCredit extends Model
{
    protected $table    = 'store_credits';
    protected $fillable = ['user_id','amount','type','reason','reference_id','reference_type'];
    protected $casts    = ['amount'=>'decimal:2'];
    public function user() { return $this->belongsTo(User::class); }
    public static function balanceFor(int $userId): float {
        $c=static::where('user_id',$userId)->where('type','credit')->sum('amount');
        $d=static::where('user_id',$userId)->where('type','debit')->sum('amount');
        return round($c-$d,2);
    }
}
