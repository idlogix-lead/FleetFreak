<?php

namespace App\Http\Controllers;

use App\Models\UnitMeasure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class UnitTypeController
 * @package App\Http\Controllers
 */
class UnitMeasureController extends Controller
{

    static $ignores = [];
    public $my_companies;
    static $role_module_id = 28;
    // 28
    function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }
    /*
    /**
     * Display a listing of the resource.
     *
     * *
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"UnitMeasure",
                'link'=>route("unit-types.index"),
                'active'=>true,
            ]
        ];
        $company_id = auth()->user()->active_company();
        $perPage = $request->input('perPage', 10);

        $unitTypes = UnitMeasure::where('company_id',$company_id)->paginate($perPage);
        // dd($unitTypes);

        return view('unit-type.index', compact('unitTypes','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $unitTypes->perPage());
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
                'name'=>"UnitMeasure",
                'link'=>route("unit-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("unit-types.create"),
                'active'=>true,
            ]
        ];
        $unitType = new UnitMeasure();
        return view('unit-type.create', compact('unitType','breadcrumbs'));
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
            'uncefact_code'=>['nullable'],
            // 'uom_code'=>['nullable'],
            'symbol'=>['nullable'],
            'uom_type'=>['nullable'],
            'symbol'=>['nullable'],
            'is_active'=>['nullable'],
            'is_default'=>['nullable'],
            'std_precision'=>['nullable'],
            'cost_precision'=>['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // dd($data);
        $data['created_by'] = auth()->user()->id;
        $payload = [];
        $payload['data'] = $data;
        UnitMeasure::store_unit_of_measure($payload);

        

        return redirect()->route('unit-types.index')->with('success', 'UnitMeasure created successfully.');
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
                'name'=>"UnitMeasure",
                'link'=>route("unit-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("unit-types.show",$id),
                'active'=>true,
            ]
        ];
        $unitType = UnitMeasure::where('company_id',auth()->user()->active_company())->find($id);

        return view('unit-type.show', compact('unitType','breadcrumbs'));
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
                'name'=>"UnitMeasure",
                'link'=>route("unit-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("unit-types.edit",$id),
                'active'=>true,
            ]
        ];
        $unitType = UnitMeasure::where('company_id',auth()->user()->active_company())->find($id);

        return view('unit-type.edit', compact('unitType','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  UnitMeasure $unitType
     * *
     */
    public function update(Request $request,$id)
    {
        // Validate the request data
        $unit_measure = UnitMeasure::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
			'description' => ['nullable'],
            'uncefact_code'=>['nullable'],
            // 'uom_code'=>['nullable'],
            'symbol'=>['nullable'],
            'uom_type'=>['nullable'],
            'symbol'=>['nullable'],
            'is_active'=>['nullable'],
            'is_default'=>['nullable'],
            'std_precision'=>['nullable'],
            'cost_precision'=>['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $payload = [];
        $payload['data'] = $data;
        $payload['unit_measure'] = $unit_measure;
        UnitMeasure::update_unit_of_measure($payload);

        return redirect()->route('unit-types.index')
            ->with('success', 'UnitMeasure updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $unitType = UnitMeasure::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('unit-types.index')
            ->with('success', 'UnitMeasure deleted successfully');
    }
}
