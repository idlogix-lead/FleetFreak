<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    static $role_module_id = 32;
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

    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"Product",
                'link'=>route("products.index"),
                'active'=>true,
            ]
        ];
        $perPage = $request->input('perPage', 10);
        $products = Product::where('company_id',auth()->user()->active_company())->paginate($perPage);

        return view('product.index', compact('products','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $products->perPage());
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
                'name'=>"Product",
                'link'=>route("products.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("products.create"),
                'active'=>true,
            ]
        ];
        $product = new Product();
        return view('product.create', compact('product','breadcrumbs'));
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
        $payload =[];
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'sku_no' => ['required'],
			'description' => ['nullable'],
			'product_category_id' => ['required'],
			'product_sub_category_id' => ['required'],
			'product_image' => ['nullable'],
			'cost_price' => ['required'],
			'sale_price' => ['required'],
			'unit' => ['required']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $payload['data'] = $data;
        Product::store_product($payload);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
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
                'name'=>"Product",
                'link'=>route("products.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("products.show",$id),
                'active'=>true,
            ]
        ];
        $product = Product::where('company_id',auth()->user()->active_company())->find($id);

        return view('product.show', compact('product','breadcrumbs'));
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
                'name'=>"Product",
                'link'=>route("products.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("products.edit",$id),
                'active'=>true,
            ]
        ];
        $product = Product::where('company_id',auth()->user()->active_company())->find($id);

        return view('product.edit', compact('product','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Product $product
     * *
     */
    public function update(Request $request,$product)
    {
        // Validate the request data
        $payload = [];
        $product = Product::find($product);
        $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'sku_no' => ['required'],
        'description' => ['nullable'],
        'product_category_id' => ['required'],
		'product_sub_category_id' => ['required'],
        'product_image' => ['nullable'],
        'cost_price' => ['required'],
        'sale_price' => ['required'],
        'unit' => ['required']
    ]);
    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $payload['data'] = $data;
        $payload['product'] = $product;
        Product::update_product($payload);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $product = Product::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
