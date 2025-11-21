<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends BaseModel
{
    use HasFactory;
    protected $guarded = [];



    // public static function relatedCities($cities){

    //     return $cities?? null;
    // }
    public static function getCitiesByCountry($countryName)
    {
        return self::where('country', $countryName)->get();
    }
    // public static function getAllCountries()
    // {
    //     return self::all();
    // }
    public static function fetchCountry(){
        return City::groupBy("country")->select("country")->get()->pluck("country");
    }

    public function fetchCity($country){
        return City::where("country",$country)->select("city")->get()->pluck("city");
    }
}
