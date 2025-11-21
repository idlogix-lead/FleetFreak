<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDetail extends BaseModel
{
    use HasFactory;
    protected $guarded = [];



    public function packages()
    {
        return $this->belongsTo(Package::class);

    }
    public function routes()
    {
        return $this->belongsTo(Route::class);

    }
}
