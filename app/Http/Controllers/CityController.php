<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\City;

class CityController extends Controller
{
    function __construct(){
        $this->middleware('RolePermissions');

    }    
    static $ignores = ['getCities'=>true];
    public function getCities(Request $request)
    {
        $country = $request->input('country');
        // dd($country);
        $cities = City::getCitiesByCountry($country);
        // dd($cities);

        return response()->json($cities);
    }
    // public function getCities(Request $request)
    // {
    //     // dd($request->input('country_name'));
    //     $countryName = $request->input('country_name');
    //     $cities = City::where('country', $countryName)->get();
    //     // City::relatedCities($cities);
    //     // dd($cities);
    //     return response()->json($cities);
    // }
}
