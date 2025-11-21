<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteRate extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function routes()
    {
        return $this->belongsTo(Route::class);

    }
    public function comapny()
    {
        return $this->belongsTo(Company::class);

    }
}
