<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialInoutLine extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function materialInout()
    {
        return $this->belongsTo(\App\Models\MaterialInout::class, 'm_inout_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }
    public function orderLine()
    {
        return $this->belongsTo(\App\Models\OrderDetail::class, 'order_detail_line_id', 'id');
    }
}
