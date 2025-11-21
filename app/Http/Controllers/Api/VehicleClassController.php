<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Class VehicleClassController
 * @package App\Http\Controllers
 */
class VehicleClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $ignores = [
    // 'api_index' => true,
    'api_vehicle_class' => true,
    'api_store' =>true
    // 'store_vehicle_class'=>true
    ];
    static $role_module_id = 20;
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
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    //     $this->middleware('RolePermissions');

    // }
    public function api_index()
    {

        // $vehicleClass = VehicleClass::where('id', 1)->get();
        // $uniqueVehiclemodel = DB::table('vehicles')
        //     ->select('vehicle_model_id' , 'vehicle_class_id ')
        //     ->distinct()
        //     ->pluck('vehicle_model_id');
        // $vehiclemodels = DB::table('vehicle_models')
        //     ->whereIn('id', $uniqueVehiclemodel)
        //     ->get();

        // $vehicleClassArray = $vehicleClass->toArray();
        // $vehicleClass = VehicleClass::all();

        // return response()->json([
        //     'vehicle models' => $vehiclemodels,
        // ]);
        $uniqueVehicleModels = DB::table('vehicles')
            ->select('vehicle_model_id', 'vehicle_class_id')
            ->distinct()
            ->get();

        // Fetch vehicle models and associated vehicle class details
        $vehicleModels = DB::table('vehicle_models')
            ->whereIn('id', $uniqueVehicleModels->pluck('vehicle_model_id'))
            ->get();

        // Fetch vehicle classes based on vehicle class IDs
        $vehicleClasses = DB::table('vehicle_classes')
            ->whereIn('id', $uniqueVehicleModels->pluck('vehicle_class_id'))
            ->get();

        // Combine models with their classes
        $result = $vehicleModels->map(function ($model) use ($vehicleClasses, $uniqueVehicleModels) {
            $vehicleClassId = $uniqueVehicleModels->firstWhere('vehicle_model_id', $model->id)->vehicle_class_id;
            $vehicleClass = $vehicleClasses->firstWhere('id', $vehicleClassId);

            return [
                'model' => $model,
                'vehicle_class' => $vehicleClass,
            ];
        });

        // Return as JSON response
        return response()->json([
            'data' => $result,
        ]);
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
                'name' => "VehicleClass",
                'link' => route("vehicle_classes.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("vehicle_classes.create"),
                'active' => true,
            ],
        ];
        $vehicleClass = new VehicleClass();
        return view('vehicle-class.create', compact('vehicleClass', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function api_store(Request $request)
    {
        $payload = [];
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            'seats_allow' => ['required'],
            'bags_allow' => ['required'],
            'company_id' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $vehicleClassData = $validator->validated();
        $vehicleClassData['created_by'] = auth()->user()->id;
        $payload['vehicleClassData'] = $vehicleClassData;
       $vehicleClass= VehicleClass::store_vehicle_class($payload);
       //    dd($vehicleClass);

      return response()->json(['success' => true, 'message' => ' Vehicle Class created successfully', 'data' => $vehicleClass], 200);

        // return redirect()->route('vehicle_classes.index')->with('success', 'VehicleClass created successfully.');
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
                'name' => "VehicleClass",
                'link' => route("vehicle_classes.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("vehicle_classes.show", $id),
                'active' => true,
            ],
        ];
        $vehicleClass = VehicleClass::find($id);

        return view('vehicle-class.show', compact('vehicleClass', 'breadcrumbs'));
    }

    // get vehicles class
    public function api_vehicle_class(){
        // dd('knrfk');
         $companyId = auth()->user()->active_company() ?? null;
        // dd($companyId);

        $vehicleClass = VehicleClass::when($companyId, function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId);
                })->get();
                // dd($vehicleModels);
        return response()->json(['success'=>true, 'data'=>$vehicleClass],200);
        // return response()->json(['success' => true, 'data' => $vehicleClass], 200);

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
                'name' => "VehicleClass",
                'link' => route("vehicle_classes.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("vehicle_classes.edit", $id),
                'active' => true,
            ],
        ];
        $vehicleClass = VehicleClass::find($id);

        return view('vehicle-class.edit', compact('vehicleClass', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  VehicleClass $vehicleClass
     * *
     */
    public function update(Request $request, VehicleClass $vehicleClass)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), VehicleClass::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $vehicleClass->update($data);

        return redirect()->route('vehicle_classes.index')
            ->with('success', 'VehicleClass updated successfully');
    }
    // public function store_vehicle_class(){
    //     // dd('fff');
    // }
    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $vehicleClass = VehicleClass::find($id)->delete();

        return redirect()->route('vehicle_classes.index')
            ->with('success', 'VehicleClass deleted successfully');
    }
}
