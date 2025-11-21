<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalUseline extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function inventoryConsumption()
    {
        return $this->belongsTo(InventoryConsumption::class,'inventory_consumption_id','id');
    }

}
