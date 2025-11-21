<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoadType;
class LoadTypeController extends Controller
{
    //

    static $ignores = [
        // 'api_index' => true,
        // 'api_store' => true,
        // 'api_show' =>true,
        // 'api_update' => true,
        // 'api_destroy' => true,
        // 'api_edit'=>true
    ];

    static $role_module_id = 27;
    public $my_companies;
    // 27
    function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }

    public function api_index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"LoadType",
                'link'=>route("load-types.index"),
                'active'=>true,
            ]
        ];
        $company_id = auth()->user()->active_company();
        // $perPage = $request->input('perPage', 10);

        // $loadTypes = LoadType::where('company_id', $company_id)->paginate($perPage);
        $loadTypes = LoadType::where('company_id', $company_id)->get();
        return response()->json(['message' => 'success', 'Load_types' => $loadTypes, 'breadcrumbss' => $breadcrumbs]);


    }
}
