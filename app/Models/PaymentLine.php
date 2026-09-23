<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLine extends BaseModel
{
    use HasFactory;
    use BelongsToOrganization;

    protected $fillable = [
        'order_id', 'order_detail_id', 'payment_header_id', 'company_id',
        'total_amount', 'transaction_date', 'description',
        'payment_type', 'reference_no',
        'created_at', 'updated_at',
    ];

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
