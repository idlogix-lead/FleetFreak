<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehicleCompany;
use App\Models\Vehicle;
use App\Models\VehicleClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

/**
 * Class VehicleController
 * @package App\Http\Controllers
 */
class VehicleController extends Controller
{
    static $role_module_id = 5;

    static $ignores = [
        'api_index' => true,
        'api_store' => true,
        'api_update' => true,
        'api_destroy' => true,
        'getVehicleClassIdByPartner'=>true,
        'api_total_vehicles'=>true,
        'api_vehicle_status'=>true,
        'getVehicleDetailByPartner'=>true,
        'api_store_vehicle_model'=>true,
        'api_vehicle_manager'=>true
    ];
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }
    public function api_index()
    {
        $user = Auth::user();
        $company_id = auth()->user()->active_company();
        $breadcrumbs = [
            [
                'name' => "Vehicle",
                'link' => route("vehicles.index"),
                'active' => true,
            ],
        ];
        // $vehicles = Vehicle::checkGlobal(5)->paginate();
        $vehicles = Vehicle::where('company_id',$company_id)->get();
        $vehicles->load('car_company', 'vehicleClass');

        return response()->json(['message' => 'success', 'vehicle' => $vehicles, 'breadcrumbss' => $breadcrumbs]);
    }

    public function api_total_vehicles(){
        $user = Auth::user();
        $company_id = auth()->user()->active_company();
        $vehicles = Vehicle::where('company_id',$company_id)->count();
        return response()->json(['message' => 'success', 'vehicle' => $vehicles]);

    }

    public function api_vehicle_status(){
        $company_id = auth()->user()->active_company();
        // dd($company_id);
        $vehicle_status= [
            'active' => Vehicle::where('company_id', $company_id)->where('is_status', 'active')->count(),
            'inactive' => Vehicle::where('company_id', $company_id)->where('is_status', 'inactive')->count(),
            'sold' => Vehicle::where('company_id', $company_id)->where('is_status', 'sold')->count(),
        ];
        return response()->json(['message' => 'success', 'vehicle_stats' => $vehicle_status]);

    }

    public function api_vehicle_manager(){
        // dd(User::get());
        $vehicle_manager=User::vehicleManagerDropdown();
        return response()->json(['message' => 'success', 'vehicle_managers' => $vehicle_manager]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    public function create()
    {
        $breadcrumbs = [
            [
                'name' => "Vehicle",
                'link' => route("vehicles.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("vehicles.create"),
                'active' => true,
            ],
        ];
        $vehicle = new Vehicle();
        $company = VehicleCompany::get();
        $vehicle_class = VehicleClass::get();

        return view('vehicle.create', compact('vehicle', 'breadcrumbs', 'company', 'vehicle_class'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function api_store(Request $request)
    {
        // Validate the request data

        // $validator = Validator::make($request->all(), [
        // 'vehicle_identification_number' => ['required','alpha_num'],
        // 'chassis_no' => ['required'],
        // 'route_permits_no' => ['required'],
        // 'route_permits_expiry_date' => ['required'],
        // 'fitness_certificate_no' => ['required'],
        // 'insurance_no' => ['required'],
        // 'insurance_provider' => ['required'],
        // 'prefix_insurance_provider_contact_no' => ['required'],
        // 'insurance_provider_contact_no' => ['required'],
        // 'insurance_start_date' => ['required'],
        // 'model' => ['required','string'],
        // // 'car_company_id'=> ['required'],
        // 'driver_id'=> ['nullable'],
        // // 'year' => ['required'],
        // 'year' => ['nullable'],
        // 'color' => ['nullable'],
        // 'vehicle_no' => ['required','string'],
        // 'registration_no' => ['required','string'],
        // // 'ownership' => ['required'],
        // 'ownership' => ['nullable'],
        // 'vehicle_manager_id'=> ['required'],

        // // 'fuel_type' => ['required'],
        // 'fuel_type' => ['nullable'],

        // 'engine_type' => ['nullable'],
        // // 'vehicle_class_id'=> ['required'],
        // // 'transmission_type' => ['required'],
        // 'transmission_type' => ['nullable'],

        // 'weight'=> ['nullable'],
        // 'milage'=>['nullable'],
        // 'image'=> ['nullable','image','max:2048'],
        // // 'car_condition'=> ['required'],
        // 'car_condition'=> ['nullable'],

        // 'is_ac'=> ['nullable'],
        // 'is_status'=>['nullable'],
        // 'company_id' => ['nullable'],
        // 'maintenance_interval_days'=>['required'],
        // // 'maintenance_oilchange_interval_km'=>['required'],
        // 'maintenance_oilchange_interval_km'=>['nullable'],



        // ]);



        $validator = Validator::make($request->all(), [
            'vehicle_identification_number' => ['required','alpha_num'],
            'chassis_no' => ['required'],
            'route_permits_no' => ['required'],
            'route_permits_expiry_date' => ['required'],
            'fitness_certificate_no' => ['required'],
            'insurance_no' => ['required'],
            'insurance_provider' => ['required'],
            'prefix_insurance_provider_contact_no' => ['required'],
            'insurance_provider_contact_no' => ['required'],
            'insurance_start_date' => ['required'],
            'model' => ['required'],
            // 'car_company_id'=> ['required'],
            'driver_id'=> ['nullable'],
            // 'year' => ['required'],
            'year' => ['nullable'],
            'color' => ['nullable'],
            'vehicle_no' => ['required','string'],
            'registration_no' => ['required','string'],
            // 'ownership' => ['required'],
            'ownership' => ['nullable'],
            'vehicle_manager_id'=> ['required'],
    
            // 'fuel_type' => ['required'],
            'fuel_type' => ['nullable'],
    
            'engine_type' => ['nullable'],
            // 'vehicle_class_id'=> ['required'],
            // 'transmission_type' => ['required'],
            'transmission_type' => ['nullable'],
    
            'weight'=> ['nullable'],
            'milage'=>['nullable'],
            'image'=> ['nullable','image','max:2048'],
            // 'car_condition'=> ['required'],
            'car_condition'=> ['nullable'],
    
            'is_ac'=> ['nullable'],
            'is_status'=>['nullable'],
            'company_id' => ['nullable'],
            'maintenance_interval_days'=>['required'],
            // 'maintenance_oilchange_interval_km'=>['required'],
            'maintenance_oilchange_interval_km'=>['nullable'],
    
    
    
        ]);
        if ($validator->fails()) {
            // dd($validator->errors());
            // return back()->with('errors', $validator->errors());
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // dd($data);

        $created_by = auth()->user()->id;
        $payload = [
            'data' => $data,
            'created_by' => $created_by,
        ];
        // dd($payload);
        Vehicle::store_vehicle($payload);

        //$vehicle = Vehicle::create($data);

        // return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
        return response()->json(['success' => 'vehicle created successfully', 'data' => $payload]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    public function show($id)
    {
        $breadcrumbs = [
            [
                'name' => "Vehicle",
                'link' => route("vehicles.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("vehicles.show", $id),
                'active' => true,
            ],
        ];
        $user = Auth::user();
        $company_id = auth()->user()->active_company();
        $vehicle = Vehicle::checkGlobal(5)->where('company_id',$company_id)->find($id);

        return view('vehicle.show', compact('vehicle', 'breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * *
     */
    public function edit($id)
    {
        $breadcrumbs = [
            [
                'name' => "Vehicle",
                'link' => route("vehicles.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("vehicles.edit", $id),
                'active' => true,
            ],
        ];
        $user = Auth::user();
        $company_id = auth()->user()->active_company();

        $vehicle = Vehicle::checkGlobal(5)->where('company_id',$company_id)->find($id);
        $company = VehicleCompany::where('company_id',$company_id)->get();
        $vehicle_class = VehicleClass::get();

        return view('vehicle.edit', compact('vehicle', 'breadcrumbs', 'company', 'vehicle_class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Vehicle $vehicle
     * *
     */
    public function api_update(Request $request, Vehicle $vehicle)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
        'vehicle_identification_number' => ['required','alpha_num'],
        'chassis_no' => ['required'],
        'route_permits_no' => ['required'],
        'route_permits_expiry_date' => ['required'],
        'fitness_certificate_no' => ['required'],
        'insurance_no' => ['required'],
        'insurance_provider' => ['required'],
        'prefix_insurance_provider_contact_no' => ['required'],
        'insurance_provider_contact_no' => ['required'],
        'insurance_start_date' => ['required'],
        'model' => ['required','string'],
        // 'vehicle_company_id'=> ['required'],
        'driver_id'=> ['nullable'],
        // 'year' => ['required'],
        'year' => ['nullable'],
        'color' => ['nullable'],
        'vehicle_no' => ['required','string'],
        'registration_no' => ['required','string'],
        // 'ownership' => ['required'],
        'ownership' => ['nullable'],
        'vehicle_manager_id'=> ['required'],

        // 'fuel_type' => ['required'],
        'fuel_type' => ['nullable'],

        'engine_type' => ['nullable'],
        // 'vehicle_class_id'=> ['required'],
        // 'transmission_type' => ['required'],
        'transmission_type' => ['nullable'],

        'weight'=> ['nullable'],
        'milage'=>['nullable'],
        'image'=> ['nullable','image','max:2048'],
        // 'car_condition'=> ['required'],
        'car_condition'=> ['nullable'],

        'is_ac'=> ['nullable'],
        'is_status'=>['nullable'],
        'company_id' => ['nullable'],
        'maintenance_interval_days'=>['required'],
        // 'maintenance_oilchange_interval_km'=>['required'],
        'maintenance_oilchange_interval_km'=>['nullable'],



        ]);
        if ($validator->fails()) {
            // return back()->with('errors', $validator->errors());
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $data = $validator->validated();

        //     if ($request->hasFile('image')) {
        //         $image = $request->file('image');
        //         $imageName = time() . '_' . $image->getClientOriginalName().$data['id'];
        //         $image->move(public_path('storage/resources_images/uploads'), $imageName);
        //         $data['image'] = 'resources_images/uploads/' . $imageName; // Set the image path in the profile
        //     }
        //     else {
        // // If no image is uploaded, set the default image path
        //         $data['image'] = 'resources_images/default/default.jpeg';
        //     }

        $updated_by = auth()->user()->id;
        $payload = [
            'data' => $data,
            'vehicle' => $vehicle,
            'updated_by' => $updated_by,
        ];

        Vehicle::update_vehicle($payload);

        //$vehicle->update($data);

        // return redirect()->route('vehicles.index')
        //     ->with('success', 'Vehicle updated successfully');
        return response()->json(['success' => "vehicle update successfully", "data" => $payload]);
    }



    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        $user = Auth::user();
        $company_id = auth()->user()->active_company();
        $vehicle = Vehicle::where('company_id', $company_id)->findOrFail($id)->delete();

        // return redirect()->route('vehicles.index')
        //     ->with('success', 'Vehicle deleted successfully');
        return response()->json(['success' => 'vheicle detlete successfully']);
    }
    public function changeStatus(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        if ($request->has('status') && in_array('sold', $request->status)) {

            $vehicle->update(['is_status' => 'sold', 'reason' => null]);
        } elseif ($request->has('status') && in_array('inactive', $request->status)) {
            $vehicle->update(['is_status' => 'inactive', 'reason' => $request['reason']]);

        } else {
            $vehicle->update(['is_status' => 'active', 'reason' => null]);

        }
        return redirect()->back()->with('success', 'Vehicle status updated successfully.');

    }

    public function getVehicleClassIdByPartner(Request $request)
    {
        // Assume the authenticated user has a partner_id
        $partner_id = $request->user()->partner_id;

        // Retrieve the vehicle_class_id from the vehicles table where driver_id equals partner_id
        $vehicleClassIds = DB::table('vehicles')
            ->where('driver_id', $partner_id)
            ->pluck('vehicle_class_id');

        return response()->json([
            'vehicle_class_ids' => $vehicleClassIds,
        ]);
    }
    public function getVehicleDetailByPartner(Request $request)
    {
        // Assume the authenticated user has a partner_id
        $partner_id = $request->user()->partner_id;

        // Retrieve the vehicle_class_id from the vehicles table where driver_id equals partner_id
        $vehicledetails = Vehicle::
            where('driver_id', $partner_id)->with('vehicleModel')->get();
            // ->pluck('vehicle_model_id');

        return response()->json([
            'vehicledetails' => $vehicledetails,
        ]);
    }
}
