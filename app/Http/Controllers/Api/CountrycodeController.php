<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CountryCode;
use Illuminate\Http\Request;

class CountrycodeController extends Controller
{
   public function api_index()
   {
      // dd('api_index');
    $phonecode=CountryCode::all();
    return response()->json(['success'=>true,
    'phonecode'=>$phonecode]);
   }

}
