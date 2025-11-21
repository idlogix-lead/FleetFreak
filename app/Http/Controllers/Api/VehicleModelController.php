<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleModelController extends Controller
{

     static $ignores = [
        'api_index'=>true,
        'api_store_vehicle_model'=>true,
    ];

    static $role_module_id = 22;
    public $my_companies;
    public function __construct()

    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });

    }
    public function api_index()
    {
        $user=auth()->user();
        // $company=auth()->user()->active_company();
        // $vehiclemodel=VehicleModel::where('company_id',$company)->get();
        // return response()->json(['success'=>true, 'data'=>$vehiclemodel],200);

        // $company = auth()->user()->active_company();
        // dd($user);
        $company = $user->companies->first();
    //    dd(auth()->user()->active_company());
        $companyId = auth()->user()->active_company() ?? null;
        // dd($companyId);

        // $vehicleModels = VehicleModel::with('vehicleClass')->where('company_id', $companyId)->get();
                // dd($vehicleModels);
        $vehicleModels = VehicleModel::with('vehicleClass','carCompany')->where('company_id', $companyId)->get();
        return response()->json(['success'=>true, 'data'=>$vehicleModels],200);

    }

    public function api_store_vehicle_model(Request $request){

        // dd('nfrneo');
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            'vehicle_company_id' => ['required'],
            'vehicle_class_id' => ['required'],
            // 'company_id' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $user = auth()->user();
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->active_company() ?? null;
        // dd($data);
        $vehicleModel = VehicleModel::create($data);
        return response()->json(['success' => true, 'message'=>"vehicle model created successfully",'data' => $vehicleModel], 200);



    }
}
