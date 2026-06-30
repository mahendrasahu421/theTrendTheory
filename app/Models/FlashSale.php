<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $table    = 'flash_sales';
    protected $fillable = ['title','banner_image','discount_percent','starts_at','ends_at','is_active'];
    protected $casts    = ['starts_at'=>'datetime','ends_at'=>'datetime','discount_percent'=>'decimal:2','is_active'=>'boolean'];

    public function products() { return $this->belongsToMany(Product::class,'flash_sale_products','flash_sale_id','product_id')->withPivot('special_price','stock_limit','sold_count'); }
    public function isLive(): bool { return $this->is_active && now()->between($this->starts_at,$this->ends_at); }
    public static function current() { return static::where('is_active',true)->where('starts_at','<=',now())->where('ends_at','>=',now())->with('products')->first(); }
}
