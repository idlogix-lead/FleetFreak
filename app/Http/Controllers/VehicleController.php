<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleClass;
use App\Models\VehicleCompany;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VehicleExport;

use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Models\TimeLog;
/**
 * Class VehicleController
 * @package App\Http\Controllers
 */
class VehicleController extends Controller
{
    static $ignores = ['getVehicleLocation'=>true];
    public $my_companies;
    static $role_module_id = 5;
    function __construct(){
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"Vehicle",
                'link'=>route("vehicles.index"),
                'active'=>true,
            ]
        ];

        $user = auth()->user();
        $perPage = $request->input('perPage', 10);

        $company = $user->companies->first();
        $companyId = auth()->user()->active_company() ?? null;

        $vehicles = Vehicle::when($companyId, function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        })->checkGlobal(5)->paginate($perPage);

        return view('vehicle.index', compact('vehicles','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $vehicles->perPage());
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
                'name'=>"Vehicle",
                'link'=>route("vehicles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("vehicles.create"),
                'active'=>true,
            ]
        ];
        $vehicle = new Vehicle();
        $company = VehicleCompany::where('company_id',auth()->user()->active_company())->get();
        $vehicle_class = VehicleClass::where('company_id',auth()->user()->active_company())->get();

        return view('vehicle.create', compact('vehicle','breadcrumbs','company','vehicle_class'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
        'vehicle_identification_number' => ['required','alpha_num'],
        'chassis_no' => ['nullable'],
        'route_permits_no' => ['nullable'],
        'route_permits_expiry_date' => ['nullable'],
        'fitness_certificate_no' => ['nullable'],
        'insurance_no' => ['nullable'],
        'insurance_provider' => ['nullable'],
        'prefix_insurance_provider_contact_no' => ['nullable'],
        'insurance_provider_contact_no' => ['nullable'],
        'insurance_start_date' => ['nullable'],
        'model' => ['required','string'],
        // 'vehicle_company_id'=> ['required'],
        'driver_id'=> ['nullable'],
        // 'year' => ['required'],
        'year' => ['nullable'],
        'color' => ['nullable'],
        'vehicle_no' => ['nullable'],
        'registration_no' => ['nullable','string'],
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
        'maintenance_interval_days'=>['nullable'],
        // 'maintenance_oilchange_interval_km'=>['required'],
        'maintenance_oilchange_interval_km'=>['nullable'],



    ]);
        if ($validator->fails()) {
            // dd($validator->errors());
            return back()->with('errors', $validator->errors())->withInput();

            // return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();


// dd($data);

        $created_by = auth()->user()->id;

        $payload = [
            'data' => $data,
            'created_by'=>$created_by,
        ];
        // dd($payload);
        Vehicle::store_vehicle($payload);

        //$vehicle = Vehicle::create($data);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
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
                'name'=>"Vehicle",
                'link'=>route("vehicles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("vehicles.show",$id),
                'active'=>true,
            ]
        ];
        $vehicle = Vehicle::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);

        return view('vehicle.show', compact('vehicle','breadcrumbs'));
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
                'name'=>"Vehicle",
                'link'=>route("vehicles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("vehicles.edit",$id),
                'active'=>true,
            ]
        ];
        $vehicle = Vehicle::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);
        $company = VehicleCompany::where('company_id',auth()->user()->active_company())->get();
        $vehicle_class = VehicleClass::where('company_id',auth()->user()->active_company())->get();

        return view('vehicle.edit', compact('vehicle','breadcrumbs','company','vehicle_class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Vehicle $vehicle
     * *
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        // Validate the request data
       $validator = Validator::make($request->all(), [
                'vehicle_identification_number' => ['required', 'alpha_num'],
                'chassis_no' => ['nullable'],
                'route_permits_no' => ['nullable'],
                'route_permits_expiry_date' => ['nullable'],
                'fitness_certificate_no' => ['nullable'],
                'insurance_no' => ['nullable'],
                'insurance_provider' => ['nullable'],
                'prefix_insurance_provider_contact_no' => ['nullable'],
                'insurance_provider_contact_no' => ['nullable'],
                'insurance_start_date' => ['nullable'],
                'model' => ['required', 'string'],
                // 'vehicle_company_id'=> ['required'],
                'driver_id' => ['nullable'],
                // 'year' => ['required'],
                'year' => ['nullable'],
                'color' => ['nullable'],
                'vehicle_no' => ['nullable', 'string'],
                'registration_no' => ['nullable'],
                // 'ownership' => ['required'],
                'ownership' => ['nullable'],

                // 'fuel_type' => ['required'],
                'fuel_type' => ['nullable'],

                'engine_type' => ['nullable'],
                // 'vehicle_class_id'=> ['required'],
                'vehicle_manager_id'=> ['required'],
                // 'transmission_type' => ['required'],
                'transmission_type' => ['nullable'],

                'weight' => ['nullable'],
                'milage' => ['nullable'],
                'image' => ['nullable', 'image', 'max:2048'],
                // 'car_condition'=> ['required'],
                'car_condition' => ['nullable'],

                'is_ac' => ['nullable'],
                'is_status' => ['nullable'],
                'company_id' => ['nullable'],
                'maintenance_interval_days' => ['nullable'],
                // 'maintenance_oilchange_interval_km'=>['required'],
                'maintenance_oilchange_interval_km' => ['nullable'],

            ]);

        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
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
            'vehicle'=>$vehicle,
            'updated_by'=>$updated_by,
        ];

        Vehicle::update_vehicle($payload);

        //$vehicle->update($data);

        return redirect()->back()
            ->with('success', 'Vehicle updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully');
    }
    public function changeStatus(Request $request,$id){
        $vehicle = Vehicle::findOrFail($id);
        $jsonreason = $request['reason'];
        if (is_array($jsonreason) && isset(array_values($jsonreason)[0])) {
            $reason = array_values($jsonreason)[0];
        } else {
            $reason = null;
        }

        // dd(array_values($reason)[0]);
        if ($request->has('status') && in_array('sold', $request->status)){

            $vehicle->update(['is_status'=> 'sold','reason'=>null]);
        }
        elseif($request->has('status') && in_array('inactive', $request->status)){
            $vehicle->update(['is_status'=> 'inactive','reason'=>$reason]);

        }
        else{
            $vehicle->update(['is_status'=> 'active','reason'=>null]);

        }
        return redirect()->back()->with('success', 'Vehicle status updated successfully.');

    }

    public function getVehicleClassDetails(Request $request)
    {
        $vehicleClassId = $request->input('vehicle_class_id');
        $vehicleClass = VehicleClass::find($vehicleClassId);

        return response()->json([
            'seats_allow' => $vehicleClass->seats_allow,
            'bags_allow' => $vehicleClass->bags_allow
        ]);
    }

    public function getVehicleLocation(Request $request)
    {
        $activeVehicles = Vehicle::where('is_status', 'active')->whereHas('orderDetails',function($query){
            $query->where('status','incomplete');
        })->pluck('vehicle_no', 'id');

        // Fetch all vehicles or apply filter if a specific vehicle is selected
        $vehicleNames = $request->input('vehicle_id')
                        ? [$activeVehicles[$request->vehicle_id]]
                        : Vehicle::where('is_status', 'active')->whereHas('orderDetails',function($query){
                            $query->where('status','incomplete');})
                            ->pluck('vehicle_no')->toArray();
        // dd($vehicleNames);

        // Prepare an empty array to hold all vehicle locations
        $vehicleLocations = [];
        foreach ($vehicleNames as $vehicleName) {
            // Make a POST request to the API for each vehicle
            $response = Http::asForm()->post('https://login.tracking.me/app/Mdvr_apis/vehiclecurrentinfotp.php', [
                'username' => '7030590017',
                'password' => 'ABCD1234$',
                'vehicleName' => $vehicleName,
            ]);

            // Check if the request was successful
            if ($response->successful()) {
                // Get the latitude and longitude from the response
                $locationData = $response->json();
                // dd($locationData['data']);
                if(isset($locationData['data'])){
                    $finalData = $locationData['data'];
                }
                else{
                    continue;
                }


                // Add the vehicle's location to the array
                $vehicleLocations[] = [
                    'vehicleName' => $vehicleName,
                    'latitude' => $finalData['Latitude'],
                    'longitude' => $finalData['Longitude'],
                    'direction'=>$finalData['Direction'],
                    'speed'=>$finalData['Speed']
                ];
                // dd($vehicleLocations);
            }
        }
        // dd($vehicleLocations);
        // Pass the vehicle locations data to the view
        return view('vehicle.vehicle_map', compact('vehicleLocations','activeVehicles'));
    }
    public function export(){
        return Excel::download(new VehicleExport, 'vehicles.xlsx');
    }


    public function get_time_logs()
    {
        $company_id = auth()->user()->active_company();

        $data = TimeLog::where('company_id', $company_id)->get();
         dd('$data');

        return response()->json(['data' => $data]);
    }
}
