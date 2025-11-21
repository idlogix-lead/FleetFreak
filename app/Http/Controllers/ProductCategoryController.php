<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ProductCategoryController
 * @package App\Http\Controllers
 */
class ProductCategoryController extends Controller
{
    static $role_module_id = 54;
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
                'name'=>"ProductCategory",
                'link'=>route("product-categories.index"),
                'active'=>true,
            ]
        ];
        $productCategories = ProductCategory::paginate();


        return view('product-category.index', compact('productCategories','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $productCategories->perPage());
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
                'name'=>"ProductCategory",
                'link'=>route("product-categories.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("product-categories.create"),
                'active'=>true,
            ]
        ];
        $productCategory = new ProductCategory();
        return view('product-category.create', compact('productCategory','breadcrumbs'));
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
			'name' => ['required','unique:product_categories'],
			'description' => ['nullable'],
			'material_policy' => ['nullable'],
			'default' => ['nullable'],
			'is_active' => ['nullable'],

        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $payload['data'] = $data;
        ProductCategory::store_product_category($payload);

        return redirect()->route('product-categories.index')->with('success', 'ProductCategory created successfully.');
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
                'name'=>"ProductCategory",
                'link'=>route("product-categories.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("product-categories.show",$id),
                'active'=>true,
            ]
        ];
        $productCategory = ProductCategory::find($id);

        return view('product-category.show', compact('productCategory','breadcrumbs'));
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
                'name'=>"ProductCategory",
                'link'=>route("product-categories.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("product-categories.edit",$id),
                'active'=>true,
            ]
        ];
        $productCategory = ProductCategory::find($id);

        return view('product-category.edit', compact('productCategory','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ProductCategory $productCategory
     * *
     */
    public function update(Request $request,$productCategory)
    {
        $productCategory = ProductCategory::findOrFail($productCategory);
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required',Rule::unique('product_categories')->ignore($productCategory->id)],
			'description' => ['nullable'],
			'material_policy' => ['nullable'],
			'default' => ['nullable'],
			'is_active' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $payload['data'] = $data;
        $payload['productCategory'] = $productCategory;
        ProductCategory::update_product_category($payload);

        return redirect()->route('product-categories.index')
            ->with('success', 'ProductCategory updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {

        $productCategory = ProductCategory::find($id);

        if (!$productCategory) {
            return redirect()->back()->with('error', 'Product Category not found.');
        }

        // Check if the category has subcategories
        if ($productCategory->productSubCategories()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete: This category has subcategories.');
        }
        
        $productCategory->delete();
        

        
        return redirect()->route('product-categories.index')
            ->with('success', 'ProductCategory deleted successfully');
    }
}