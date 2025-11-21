<?php

namespace App\Http\Controllers;

use App\Models\Brand;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class BrandController
 * @package App\Http\Controllers
 */
class BrandController extends Controller
{
    static $ignores = [];
    public $my_companies;
    static $role_module_id = 51;
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
                'name'=>"Brand",
                'link'=>route("brands.index"),
                'active'=>true,
            ]
        ];
        $brands = Brand::paginate();


        return view('brand.index', compact('brands','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $brands->perPage());
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
                'name'=>"Brand",
                'link'=>route("brands.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("brands.create"),
                'active'=>true,
            ]
        ];
        $brand = new Brand();
        return view('brand.create', compact('brand','breadcrumbs'));
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

        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $payload = [];
        $payload['data'] = $data;
        Brand::store_brands($payload);  
        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
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
                'name'=>"Brand",
                'link'=>route("brands.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("brands.show",$id),
                'active'=>true,
            ]
        ];
        $brand = Brand::where('company_id',auth()->user()->active_company())->find($id);

        return view('brand.show', compact('brand','breadcrumbs'));
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
                'name'=>"Brand",
                'link'=>route("brands.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("brands.edit",$id),
                'active'=>true,
            ]
        ];
        $brand = Brand::where('company_id',auth()->user()->active_company())->find($id);

        return view('brand.edit', compact('brand','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Brand $brand
     * *
     */
    public function update(Request $request,$id)
    {
        $brands = Brand::findOrFail($id);
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
        $data['updated_by'] = auth()->user()->id;
        $payload = [];
        $payload['data'] = $data;
        $payload['brands'] = $brands;
        Brand::update_brands($payload);

        return redirect()->route('brands.index')
            ->with('success', 'Brand updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $brand = Brand::find($id)->delete();

        return redirect()->route('brands.index')
            ->with('success', 'Brand deleted successfully');
    }
}
