<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceListVersion extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function priceList()
    {
        return $this->belongsTo(\App\Models\PriceList::class, 'price_list_id', 'id');
    }

    public static function versions(){
        $company_id=auth()->user()->active_company();

        return self:: where('company_id', $company_id)->where('is_active',1)->get();
    }
}
