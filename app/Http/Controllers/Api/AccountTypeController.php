<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    //
    static $ignores = [
        // 'get_accounType_ajax' => true
        'api_index' => true,
        'api_accountSubTypes' => true,
    ];
    static $role_module_id = 25;
    public $my_companies;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * *
     */
    public function api_index()
    {
        // $breadcrumbs = [
        //     [
        //         'name'=>"AccountType",
        //         'link'=>route("account-types.index"),
        //         'active'=>true,
        //     ]
        // ];

        // dd('hhhhhhh');
        $accountTypes = AccountType::with([
            'parent'
        ])->get();

        return response()->json($accountTypes);
    }
    public function api_accountSubTypes($parent_id){

        $accountSubTypes = AccountType::where('parent_id', $parent_id)->get();

        return response()->json($accountSubTypes);
    }

}
