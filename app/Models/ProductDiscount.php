<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    protected $table    = 'product_discounts';
    protected $fillable = ['product_id','label','type','value','sale_price','valid_from','valid_until','is_active'];
    protected $casts    = ['valid_from'=>'datetime','valid_until'=>'datetime','value'=>'decimal:2','sale_price'=>'decimal:2','is_active'=>'boolean'];
    public function product() { return $this->belongsTo(Product::class); }
    public function isCurrentlyActive(): bool {
        if(!$this->is_active) return false;
        if($this->valid_from  && now()->lt($this->valid_from))  return false;
        if($this->valid_until && now()->gt($this->valid_until)) return false;
        return true;
    }
}
