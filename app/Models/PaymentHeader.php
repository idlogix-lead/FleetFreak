<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHeader extends BaseModel
{
    use HasFactory;
    protected $guarded = [];
    public function paymentLines()
    {
        return $this->hasMany(PaymentLine::class);
    }
    public function partner_customer()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'customer_id', 'id');
    }
    public function partner_business()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'agent_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);

    }






}
