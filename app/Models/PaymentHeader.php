<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHeader extends BaseModel
{
    use HasFactory;
    use BelongsToOrganization;

    protected $fillable = [
        'payment_no', 'agent_id', 'customer_id', 'driver_id', 'date',
        'total_amount', 'description', 'status', 'company_id',
        'created_by', 'updated_by',
        'type', 'business_partner_id', 'actor_id',
        'created_at', 'updated_at',
    ];
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
