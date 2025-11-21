<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLine extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function order_details()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }
    public function payment_header()
    {
        return $this->belongsTo(PaymentHeader::class);
    }

    public function comapny()
    {
        return $this->belongsTo(Company::class);

    }

}
