<?php

namespace App\Http\Controllers;

use App\Models\VehicleCompany;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VehicleCompanyExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class CarCompanyController
 * @package App\Http\Controllers
 */
class VehicleCompanyController extends Controller
{
    static $ignores = [];
    public $my_companies;
    static $role_module_id = 23;

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
                'name'=>"VehicleCompany",
                'link'=>route("vehicle-companies.index"),
                'active'=>true,
            ]
        ];

        $user = auth()->user();

        $company = $user->companies->first();

        // if (!$company) {
        //     return redirect()->route('home')->with('error', 'No associated company found.');
        // }
        $companyId = auth()->user()->active_company() ?? null;
        $perPage = $request->input('perPage', 10);


        $carCompanies = VehicleCompany::when($companyId, function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        })->paginate($perPage);

        return view('car-company.index', compact('carCompanies','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $carCompanies->perPage());
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
                'name'=>"VehicleCompany",
                'link'=>route("vehicle-companies.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("vehicle-companies.create"),
                'active'=>true,
            ]
        ];
        $carCompany = new VehicleCompany();
        return view('car-company.create', compact('carCompany','breadcrumbs'));
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
        $validator = Validator::make($request->all(), VehicleCompany::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        $user=auth()->user();
        $company=auth()->user()->active_company();
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $data['company_id'] = $company ?? null;

        $carCompany = VehicleCompany::create($data);

        return redirect()->route('vehicle-companies.index')->with('success', 'VehicleCompany created successfully.');
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
                'name'=>"VehicleCompany",
                'link'=>route("vehicle-companies.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("vehicle-companies.show",$id),
                'active'=>true,
            ]
        ];
        $carCompany = VehicleCompany::where('company_id',auth()->user()->active_company())->find($id);

        return view('car-company.show', compact('carCompany','breadcrumbs'));
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
                'name'=>"VehicleCompany",
                'link'=>route("vehicle-companies.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("vehicle-companies.edit",$id),
                'active'=>true,
            ]
        ];
        $carCompany = VehicleCompany::where('company_id',auth()->user()->active_company())->find($id);

        return view('car-company.edit', compact('carCompany','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  VehicleCompany $carCompany
     * *
     */
    public function update(Request $request, VehicleCompany $carCompany)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), VehicleCompany::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $company=auth()->user()->active_company();

        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $data['company_id'] = $company ?? null;

        $carCompany->update($data);

        return redirect()->route('vehicle-companies.index')
            ->with('success', 'VehicleCompany updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $carCompany = VehicleCompany::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('vehicle-companies.index')
            ->with('success', 'VehicleCompany deleted successfully');
    }
    public function export(){
        return Excel::download(new VehicleCompanyExport, 'vehicle_companies.xlsx');
    }
}
