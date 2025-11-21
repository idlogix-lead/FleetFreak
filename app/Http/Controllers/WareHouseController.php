<?php

namespace App\Http\Controllers;

use App\Models\WareHouse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class WareHouseController
 * @package App\Http\Controllers
 */
class WareHouseController extends Controller
{
    static $ignores = [];
    public $my_companies;
    static $role_module_id = 52;
    // 28
    function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }

    public function index()
    {
        $breadcrumbs = [
            [
                'name'=>"WareHouse",
                'link'=>route("ware-houses.index"),
                'active'=>true,
            ]
        ];
        $wareHouses = WareHouse::paginate();


        return view('ware-house.index', compact('wareHouses','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $wareHouses->perPage());
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
                'name'=>"WareHouse",
                'link'=>route("ware-houses.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("ware-houses.create"),
                'active'=>true,
            ]
        ];
        $wareHouse = new WareHouse();
        return view('ware-house.create', compact('wareHouse','breadcrumbs'));
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
			'name' => ['required'],
			'description' => ['nullable'],
			'code' => ['nullable'],
			'is_active' => ['nullable'],
			'is_default' => ['nullable'],
			'in_transit' => ['nullable'],
			'address' => ['nullable'],
            // 'locator_id'=>['nullable'],
            'source_warehouse_id'=>['nullable'],
			'is_disallow_negative_inv' => ['nullable'],
            // locators validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.code'=>['required'],
            'rows.*.locator_type'=>['required'],
            'rows.*.relative_priority'=>['nullable'],
            'rows.*.aisle'=>['nullable'],
            'rows.*.bin'=>['nullable'],
            'rows.*.level'=>['nullable'],
            'rows.*.is_active'=>['nullable'],
            'rows.*.is_default'=>['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $payload = [];
        $payload['data'] = $data;
        WareHouse::store_warehouse($payload);
        return redirect()->route('ware-houses.index')->with('success', 'WareHouse created successfully.');
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
                'name'=>"WareHouse",
                'link'=>route("ware-houses.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("ware-houses.show",$id),
                'active'=>true,
            ]
        ];
        $wareHouse = WareHouse::where('company_id',auth()->user()->active_company())->find($id);

        return view('ware-house.show', compact('wareHouse','breadcrumbs'));
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
                'name'=>"WareHouse",
                'link'=>route("ware-houses.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("ware-houses.edit",$id),
                'active'=>true,
            ]
        ];
        $wareHouse = WareHouse::where('company_id',auth()->user()->active_company())->find($id);

        return view('ware-house.edit', compact('wareHouse','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  WareHouse $wareHouse
     * *
     */
    public function update(Request $request,$id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(),[
            'name' => ['required'],
			'description' => ['nullable'],
			'code' => ['nullable'],
			'is_active' => ['nullable'],
			'is_default' => ['nullable'],
			'in_transit' => ['nullable'],
			'address' => ['nullable'],
            // 'locator_id'=>['nullable'],
            'source_warehouse_id'=>['nullable'],
			'is_disallow_negative_inv' => ['nullable'],

            // locators validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.code'=>['required'],
            'rows.*.locator_type'=>['required'],
            'rows.*.relative_priority'=>['nullable'],
            'rows.*.aisle'=>['nullable'],
            'rows.*.bin'=>['nullable'],
            'rows.*.level'=>['nullable'],
            'rows.*.is_active'=>['nullable'],
            'rows.*.is_default'=>['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $warehouse = Warehouse::findOrFail($id);
        $payload = [];
        $payload['data'] = $data;
        $payload['warehouse'] = $warehouse;
        WareHouse::update_warehouse($payload);

        return redirect()->route('ware-houses.index')
            ->with('success', 'WareHouse updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $wareHouse = WareHouse::find($id)->delete();

        return redirect()->route('ware-houses.index')
            ->with('success', 'WareHouse deleted successfully');
    }
}
