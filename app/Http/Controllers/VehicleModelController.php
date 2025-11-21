<?php

namespace App\Http\Controllers;

use App\Exports\VehicleModelExport;
use App\Models\VehicleClass;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class VehicleModelController
 * @package App\Http\Controllers
 */
class VehicleModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $ignores = [];

    static $role_module_id = 22;
    public $my_companies;
    public function __construct()
    {
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
                'name' => "VehicleModel",
                'link' => route("vehicle-models.index"),
                'active' => true,
            ],
        ];
        $user = auth()->user();
        // dd($user);
        $perPage = $request->input('perPage', 10);

        $company = $user->companies->first();

        // if (!$company) {
        //     return redirect()->route('home')->with('error', 'No associated company found.');
        // }
        $companyId = auth()->user()->active_company() ?? null;

        $vehicleModels = VehicleModel::when($companyId, function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        })->paginate($perPage);

        return view('vehicle-model.index', compact('vehicleModels', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $vehicleModels->perPage());
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
                'name' => "VehicleModel",
                'link' => route("vehicle-models.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("vehicle-models.create"),
                'active' => true,
            ],
        ];

        $user = auth()->user();

        // Retrieve the company associated with the user
        $company = auth()->user()->active_company();

        // Check if the company exists
        // if (!$company) {
        //     return redirect()->route('home')->with('error', 'No associated company found.');
        // }

        // Fetch vehicle classes for the associated company
        $vehicleClasses = VehicleClass::when($company, function ($query) use ($company) {
            return $query->where('company_id', $company);
        })
        ->get();
        $vehicleModel = new VehicleModel();
        return view('vehicle-model.create', compact('vehicleModel', 'breadcrumbs'));
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
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            'vehicle_company_id' => ['required'],
            'vehicle_class_id' => ['required'],
            'company_id' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        $user = auth()->user();
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->active_company() ?? null;

        $vehicleModel = VehicleModel::create($data);

        return redirect()->route('vehicle-models.index')->with('success', 'VehicleModel created successfully.');
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
                'name' => "VehicleModel",
                'link' => route("vehicle-models.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("vehicle-models.show", $id),
                'active' => true,
            ],
        ];
        $vehicleModel = VehicleModel::where('company_id',auth()->user()->active_company())->find($id);

        return view('vehicle-model.show', compact('vehicleModel', 'breadcrumbs'));
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
                'name' => "VehicleModel",
                'link' => route("vehicle-models.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("vehicle-models.edit", $id),
                'active' => true,
            ],
        ];
        $vehicleModel = VehicleModel::where('company_id',auth()->user()->active_company())->find($id);

        return view('vehicle-model.edit', compact('vehicleModel', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  VehicleModel $vehicleModel
     * *
     */
    public function update(Request $request, VehicleModel $vehicleModel)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            'vehicle_company_id' => ['required'],
            'vehicle_class_id' => ['required'],
            'company_id' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        $user=auth()->user();
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->active_company() ?? null;

        $vehicleModel->update($data);

        return redirect()->route('vehicle-models.index')
            ->with('success', 'VehicleModel updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $vehicleModel = VehicleModel::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('vehicle-models.index')
            ->with('success', 'VehicleModel deleted successfully');
    }

    public function export()
    {
        return Excel::download(new VehicleModelExport, 'models.xlsx');
    }
}
