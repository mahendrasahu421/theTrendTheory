<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = ['order_id','courier_name','tracking_number','tracking_url','status','shipped_at','expected_delivery','delivered_at'];
    protected $casts    = ['shipped_at'=>'datetime','expected_delivery'=>'date','delivered_at'=>'datetime'];

    public function order()          { return $this->belongsTo(Order::class); }
    public function trackingUpdates(){ return $this->hasMany(ShipmentTracking::class)->latest('tracked_at'); }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'pending'=>'Pending Pickup','pickup_scheduled'=>'Pickup Scheduled','picked_up'=>'Picked Up',
            'in_transit'=>'In Transit','out_for_delivery'=>'Out for Delivery','delivered'=>'Delivered','failed'=>'Failed',
            default=>ucfirst($this->status)
        };
    }
}
