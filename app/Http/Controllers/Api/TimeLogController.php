<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeLog;

class TimeLogController extends Controller
{
    //


    public function get_time_logs(){
        $company_id = auth()->user()->active_company();

         $data=TimeLog::where('company_id',$company_id)->get();
        //  dd('$data');

         return response()->json(['data'=>$data]);
    }
}
