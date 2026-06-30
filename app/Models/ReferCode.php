<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReferCode extends Model
{
    protected $table    = 'refer_codes';
    protected $fillable = ['user_id','code','reward_type','reward_value','times_used'];
    protected $casts    = ['reward_value'=>'decimal:2'];
    public function user() { return $this->belongsTo(User::class); }
    protected static function boot() {
        parent::boot();
        static::creating(function($m) { if(empty($m->code)) $m->code=strtoupper(Str::random(8)); });
    }
}
