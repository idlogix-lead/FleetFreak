<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryCode extends BaseModel
{
    use HasFactory;
    protected $guarded = [];
    public static function phone_codes(){
        return self::all(['name','iso', 'phonecode']);
    }
}
