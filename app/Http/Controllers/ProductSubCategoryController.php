<?php

namespace App\Http\Controllers;

use App\Models\ProductSubCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ProductSubCategoryController
 * @package App\Http\Controllers
 */
class ProductSubCategoryController extends Controller
{
    static $role_module_id = 55;
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
                'name'=>"ProductSubCategory",
                'link'=>route("product-sub-categories.index"),
                'active'=>true,
            ]
        ];
        $productSubCategories = ProductSubCategory::paginate();


        return view('product-sub-category.index', compact('productSubCategories','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $productSubCategories->perPage());
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
                'name'=>"ProductSubCategory",
                'link'=>route("product-sub-categories.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("product-sub-categories.create"),
                'active'=>true,
            ]
        ];
        $productSubCategory = new ProductSubCategory();
        return view('product-sub-category.create', compact('productSubCategory','breadcrumbs'));
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
            'name' => ['required','unique:product_sub_categories'],
			'description' => 'nullable',
            'is_active' => ['nullable'],
			'is_default' => ['nullable'],
			// 'code' => 'nullable',
			'product_category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $payload['data'] = $data;
        ProductSubCategory::store_product_subcategory($payload);
        return redirect()->route('product-sub-categories.index')->with('success', 'ProductSubCategory created successfully.');
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
                'name'=>"ProductSubCategory",
                'link'=>route("product-sub-categories.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("product-sub-categories.show",$id),
                'active'=>true,
            ]
        ];
        $productSubCategory = ProductSubCategory::find($id);

        return view('product-sub-category.show', compact('productSubCategory','breadcrumbs'));
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
                'name'=>"ProductSubCategory",
                'link'=>route("product-sub-categories.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("product-sub-categories.edit",$id),
                'active'=>true,
            ]
        ];
        $productSubCategory = ProductSubCategory::find($id);

        return view('product-sub-category.edit', compact('productSubCategory','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ProductSubCategory $productSubCategory
     * *
     */
    public function update(Request $request,$productsubCategory)
    {
        $productsubCategory = ProductSubCategory::findOrFail($productsubCategory);
        // Validate the request data
        $validator = Validator::make($request->all(), [
			'name' => ['required',Rule::unique('product_sub_categories')->ignore($productsubCategory->id)],
			'description' => 'nullable',
            'is_active' => ['nullable'],
			'is_default' => ['nullable'],
			// 'code' => 'nullable',
			'product_category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $payload['data'] = $data;
        $payload['productsubCategory'] = $productsubCategory;
        ProductSubCategory::update_product_subcategory($payload);

        return redirect()->route('product-sub-categories.index')
            ->with('success', 'ProductSubCategory updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $productSubCategory = ProductSubCategory::find($id);
         // Check if the category has subcategories
         if ($productSubCategory->products()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete: This category has Products.');
        }
        
        $productSubCategory->delete();
        return redirect()->route('product-sub-categories.index')
            ->with('success', 'ProductSubCategory deleted successfully');
    }
}
