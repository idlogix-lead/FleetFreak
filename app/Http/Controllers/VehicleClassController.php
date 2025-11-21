<?php

namespace App\Http\Controllers;

use App\Models\VehicleClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    static $role_module_id = 20;
    public $my_companies;
    public function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
        // $this->middleware('checkCompanyAccess');
    }
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name' => "VehicleClass",
                'link' => route("vehicle_classes.index"),
                'active' => true,
            ],
        ];

        $user = auth()->user();
        $perPage = $request->input('perPage', 10);
        $company = $user->companies->first();

        // if (!$company) {
        //     return redirect()->route('home')->with('error', 'No associated company found.');
        // }
        $companyId = auth()->user()->active_company() ?? null;

        // Fetch vehicle classes for the associated company
        $vehicleClasses = VehicleClass::when($companyId, function ($query, $companyId) {
            return $query->where('company_id', $companyId);
        })->paginate($perPage);

        return view('vehicle-class.index', compact('vehicleClasses', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $vehicleClasses->perPage());
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
    public function store(Request $request)
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
        VehicleClass::store_vehicle_class($payload);

        return redirect()->route('vehicle_classes.index')->with('success', 'VehicleClass created successfully.');
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
        $vehicleClass = VehicleClass::where('company_id',auth()->user()->active_company())->find($id);

        return view('vehicle-class.show', compact('vehicleClass', 'breadcrumbs'));
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
        $vehicleClass = VehicleClass::where('company_id',auth()->user()->active_company())->find($id);

        return view('vehicle-class.edit', compact('vehicleClass', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  VehicleClass $vehicleClass
     * *
     */
    public function update(Request $request, $vehicleClass)
    {
        $vehicleClass = VehicleClass::find($vehicleClass);
        // dd($vehicleClass);
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
        $vehicleClassData['updated_by'] = auth()->user()->id;
        $payload['vehicleClassData'] = $vehicleClassData;
        $payload['vehicleClass'] = $vehicleClass;

        VehicleClass::update_vehicle_class($payload);

        return redirect()->route('vehicle_classes.index')
            ->with('success', 'VehicleClass updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $vehicleClass = VehicleClass::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('vehicle_classes.index')
            ->with('success', 'VehicleClass deleted successfully');
    }

}
