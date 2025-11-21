<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitMeasure;

class UnitMeasureController extends Controller
{
    //

    static $ignores = [
        'api_index' => true,
        'api_store' => true,
        'api_show' =>true,
        'api_update' => true,
        'api_destroy' => true,
        'api_edit'=>true
    ];

    public $my_companies;
    static $role_module_id = 28;
    // 28
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
                'name'=>"UnitMeasure",
                'link'=>route("unit-types.index"),
                'active'=>true,
            ]
        ];
        $company_id = auth()->user()->active_company();
        // $perPage = $request->input('perPage', 10);

        // $unitTypes = UnitMeasure::where('company_id',$company_id)->paginate($perPage);
        $unitTypes = UnitMeasure::where('company_id',$company_id)->get();

        // dd($unitTypes);
        return response()->json(['message' => 'success', 'Unit_types' => $unitTypes, 'breadcrumbss' => $breadcrumbs]);

      
    }

}
