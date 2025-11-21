<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends BaseModel
{
    use HasFactory;
    protected $guarded = [];
    static function current_currency(){
        return self::where('country','Saudi Arabia')->first();
    }
    static function current_symbol(){
        $currency = self::current_currency();

        return $currency ? $currency->symbol: null;

    }
    static function current_code(){
        $currency = self::current_currency();

        return $currency ? $currency->code: null;
    }

}
