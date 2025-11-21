<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLine extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function activity()
    {
        return $this->belongsTo(\App\Models\Activity::class, 'activity_id', 'id');
    }
    public function invoiceLines()
    {
        return $this->hasMany(InvoiceLine::class, 'activity_line_id', 'id');
    }
}
