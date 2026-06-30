<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id','name','phone','address_line','city','state','pincode','is_default'];
    protected $casts    = ['is_default'=>'boolean'];
    public function user() { return $this->belongsTo(User::class); }
    public function getFullAddressAttribute(): string { return "{$this->address_line}, {$this->city}, {$this->state} - {$this->pincode}"; }
}
