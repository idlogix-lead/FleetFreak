<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetailRouteHistory extends BaseModel
{
    use HasFactory;
    public function orderDetails()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id', 'id');
    }
}
