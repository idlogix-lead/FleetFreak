<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends BaseModel
{
    use HasFactory;
    protected $guarded = [];
    /**
     * Get all of the comments for the InvoiceLine
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoiceLineProducts()
    {
        return $this->hasMany(InvoiceLineProduct::class, 'invoice_line_id', 'id');
    }
    /**
     * Get the user associated with the InvoiceLine
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activityLine()
    {
        return $this->hasOne(ActivityLine::class, 'id', 'activity_line_id');
    }

    /**
     * Get the user that owns the InvoiceLine
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

}
