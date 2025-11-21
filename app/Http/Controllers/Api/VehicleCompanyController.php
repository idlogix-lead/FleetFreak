<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehicleCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class VehicleCompanyController extends Controller
{
    //


     static $ignores = [
        'api_index'=>true,
        'api_store'=>true
    ];

    static $role_module_id = 23;
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
        $companyId = auth()->user()->active_company() ?? null;
        // dd($companyId);

        $CarCompanies = VehicleCompany::when($companyId, function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId);
                })->get();
                // dd($vehicleModels);
        return response()->json(['success'=>true, 'data'=>$CarCompanies],200);

    }
    public function api_store(Request $request){
       // Validate the request data
        $validator = Validator::make($request->all(),[
            	'name' => 'required',
			    'description' => 'nullable',
        ]);
        if ($validator->fails()) {
            // return back()->with('errors', $validator->errors());
            return response()->json(['errors' => $validator->errors()], 400);

        }
        $user = auth()->user();
        $company = auth()->user()->active_company();
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $data['company_id'] = $company ?? null;

        $carCompany = VehicleCompany::create($data);
        return response()->json(['success' => true,'message'=>'Car Company created successfully', 'data' =>$carCompany], 200);



    }

}
