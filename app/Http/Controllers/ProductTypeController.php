<?php

namespace App\Http\Controllers;

use App\Models\ProductType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ProductTypeController
 * @package App\Http\Controllers
 */
class ProductTypeController extends Controller
{
    static $role_module_id = 56;
    public $my_companies;
    // ignored permission functions
    // static $ignores = ['partner_dropdown'=>true, 'create_customer_from_order'=>true];

    function __construct(){
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
                'name'=>"ProductType",
                'link'=>route("product-types.index"),
                'active'=>true,
            ]
        ];
        $productTypes = ProductType::paginate();


        return view('product-type.index', compact('productTypes','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $productTypes->perPage());
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
                'name'=>"ProductType",
                'link'=>route("product-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("product-types.create"),
                'active'=>true,
            ]
        ];
        $productType = new ProductType();
        return view('product-type.create', compact('productType','breadcrumbs'));
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
            'name' => 'required',
			'description' => 'nullable',
            'is_active' => 'nullable',
			'is_default' => 'nullable',
			// 'code' => 'nullable',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $payload['data'] = $data;
        ProductType::store_product_types($payload);
        return redirect()->route('product-types.index')->with('success', 'ProductType created successfully.');
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
                'name'=>"ProductType",
                'link'=>route("product-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("product-types.show",$id),
                'active'=>true,
            ]
        ];
        $productType = ProductType::find($id);

        return view('product-type.show', compact('productType','breadcrumbs'));
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
                'name'=>"ProductType",
                'link'=>route("product-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("product-types.edit",$id),
                'active'=>true,
            ]
        ];
        $productType = ProductType::find($id);

        return view('product-type.edit', compact('productType','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ProductType $productType
     * *
     */
    public function update(Request $request,$productType)
    {
        $productType=ProductType::findOrFail($productType);
        // Validate the request data
        $validator = Validator::make($request->all(),[
            'name' => 'required',
			'description' => 'nullable',
            'is_active' => 'nullable',
			'is_default' => 'nullable',
			// 'code' => 'nullable',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $payload['data'] = $data;
        $payload['productType'] = $productType;
        ProductType::update_product_types($payload);
        return redirect()->route('product-types.index')
            ->with('success', 'ProductType updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $productType = ProductType::find($id)->delete();

        return redirect()->route('product-types.index')
            ->with('success', 'ProductType deleted successfully');
    }
}
