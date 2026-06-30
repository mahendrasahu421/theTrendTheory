<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    protected $table    = 'shipment_tracking';
    protected $fillable = ['shipment_id','status','location','description','tracked_at'];
    protected $casts    = ['tracked_at'=>'datetime'];
    public function shipment() { return $this->belongsTo(Shipment::class); }
}
