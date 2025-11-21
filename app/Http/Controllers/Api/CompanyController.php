<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    static $ignores = [
        'api_activeCompany' => true,
    ];

    public $my_companies;
    static $role_module_id = 40;

    function __construct(){
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            if(!auth()->user()->is_company_admin){
                return redirect()->route('dashboard')->with('You are not allowd to access companies Module!');
            }
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }

    public function api_activeCompany(){
        // $active_company=auth()->user()->active_company();
        $active_company=auth()->user()->active_company_details()->name;
         return response()->json($active_company);
    }
}
