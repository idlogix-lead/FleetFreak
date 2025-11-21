<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function fetchCountry(){
        return City::groupBy("country")->select("country")->get()->pluck("country");
    }
    
    public function fetchCity($country){
        return City::where("country",$country)->select("city")->get()->pluck("city");
    }
}
