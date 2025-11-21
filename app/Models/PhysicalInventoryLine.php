<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalInventoryLine extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function physicalInventory()
    {
        return $this->hasMany(\App\Models\PhysicalInventory::class, 'physical_inventory_id', 'id');
    }
}
