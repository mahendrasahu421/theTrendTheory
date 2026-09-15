<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id','type','name','phone','address_line','city','state','pincode','latitude','longitude','location_source','is_default'];
    protected $casts    = ['is_default'=>'boolean'];
    public function user() { return $this->belongsTo(User::class); }
    public function setNameAttribute($value) { $this->attributes['name'] = !empty($value) ? mb_convert_case(trim($value), MB_CASE_TITLE, "UTF-8") : $value; }
    public function getFullAddressAttribute(): string { return "{$this->address_line}, {$this->city}, {$this->state} - {$this->pincode}"; }
}
