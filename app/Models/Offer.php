<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['title','description','type','image','is_active'];
    protected $casts    = ['is_active'=>'boolean'];

    public function rules()      { return $this->hasOne(OfferRule::class); }
    public function categories() { return $this->belongsToMany(Category::class,'offer_categories'); }
    public function products()   { return $this->belongsToMany(Product::class,'offer_products'); }

    public function isActive(): bool {
        if(!$this->is_active) return false;
        $r=$this->rules;
        if(!$r) return true;
        if($r->valid_from  && now()->lt($r->valid_from))  return false;
        if($r->valid_until && now()->gt($r->valid_until)) return false;
        return true;
    }
}
