<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlJournalLine extends BaseModel
{
    use HasFactory;

    protected $hidden = []; // Ensure no fields are hidden

    protected $guarded = [];
}
