<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OfferRule extends Model
{
    protected $table    = 'offer_rules';
    protected $fillable = ['offer_id','buy_quantity','get_quantity','discount_value','min_cart_amount','max_discount_amount','valid_from','valid_until'];
    protected $casts    = ['valid_from'=>'datetime','valid_until'=>'datetime','discount_value'=>'decimal:2','min_cart_amount'=>'decimal:2','max_discount_amount'=>'decimal:2'];
    public function offer() { return $this->belongsTo(Offer::class); }
}
