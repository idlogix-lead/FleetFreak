<?php

namespace App\Http\Controllers;

use App\Models\ManufacturingCompany;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ManufacturingCompanyController
 * @package App\Http\Controllers
 */
class ManufacturingCompanyController extends Controller
{
    static $ignores = [];
    public $my_companies;
    static $role_module_id = 50;


    public function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
        // $this->middleware('checkCompanyAccess');
    }

    public function index()
    {
        $breadcrumbs = [
            [
                'name'=>"ManufacturingCompany",
                'link'=>route("manufacturing-companies.index"),
                'active'=>true,
            ]
        ];
        $manufacturingCompanies = ManufacturingCompany::paginate();


        return view('manufacturing-company.index', compact('manufacturingCompanies','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $manufacturingCompanies->perPage());
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
                'name'=>"ManufacturingCompany",
                'link'=>route("manufacturing-companies.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("manufacturing-companies.create"),
                'active'=>true,
            ]
        ];
        $manufacturingCompany = new ManufacturingCompany();
        return view('manufacturing-company.create', compact('manufacturingCompany','breadcrumbs'));
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
        $validator = Validator::make($request->all(),[
            'name' => ['required'],
			'description' => ['nullable'],
			'code' => ['nullable'],
			'is_active' => ['nullable'],
			'is_default' => ['nullable'],

        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $payload = [];
        $payload['data'] = $data;
        ManufacturingCompany::store_manufacturing_company($payload);
        
        return redirect()->route('manufacturing-companies.index')->with('success', 'ManufacturingCompany created successfully.');
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
                'name'=>"ManufacturingCompany",
                'link'=>route("manufacturing-companies.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("manufacturing-companies.show",$id),
                'active'=>true,
            ]
        ];
        $manufacturingCompany = ManufacturingCompany::where('company_id',auth()->user()->active_company())->find($id);

        return view('manufacturing-company.show', compact('manufacturingCompany','breadcrumbs'));
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
                'name'=>"ManufacturingCompany",
                'link'=>route("manufacturing-companies.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("manufacturing-companies.edit",$id),
                'active'=>true,
            ]
        ];
        $manufacturingCompany = ManufacturingCompany::where('company_id',auth()->user()->active_company())->find($id);

        return view('manufacturing-company.edit', compact('manufacturingCompany','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ManufacturingCompany $manufacturingCompany
     * *
     */
    public function update(Request $request,$id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
			'description' => ['nullable'],
			'code' => ['nullable'],
			'is_active' => ['nullable'],
			'is_default' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $manufacturingCompany = ManufacturingCompany::findOrFail($id);

        $payload = [];
        $payload['data'] = $data;
        $payload['manufacturingCompany']= $manufacturingCompany;
        ManufacturingCompany::update_manufacturing_company($payload);
        return redirect()->route('manufacturing-companies.index')
            ->with('success', 'ManufacturingCompany updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $manufacturingCompany = ManufacturingCompany::find($id)->delete();

        return redirect()->route('manufacturing-companies.index')
            ->with('success', 'ManufacturingCompany deleted successfully');
    }
}
