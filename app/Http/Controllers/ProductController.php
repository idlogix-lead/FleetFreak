<?php

namespace App\Http\Controllers;


use App\Models\Invoice;
use App\Models\InvoiceDocumentType;
use App\Models\MaterialInout;
use App\Models\MaterialInoutLine;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PartnerLocation;
use App\Models\PriceList;
use App\Models\PriceListVersion;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ActivityController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    // static $ignores = ['deleteActivityRow' => true,'fetchPoLines'=>true,'fetchPurchaseOrders'=>true];
    static $ignores = ['deleteActivityRow' => true,'fetchProductPrice'=>true];
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
        $activity = new Product();
        return view('product.create', compact('activity','breadcrumbs'));
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
        // dd($request);
        $payload = [];
        $validator = Validator::make($request->all(), [
			
            'name' => ['required'],
            'sku_no' => ['required'],
			'description' => ['nullable'],
			'product_category_id' => ['required'],
			'product_sub_category_id' => ['required'],
			// 'product_image' => ['nullable'],
			// 'cost_price' => ['required'],
			// 'sale_price' => ['required'],
			'unit' => ['required'],
			'is_default' => ['nullable'],
			'is_active' => ['nullable'],
			
			// 'rma' => ['nullable'],

            // product list validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no'=>['nullable'],
            'rows.*.price_list_version_id'=>['required'],
            'rows.*.list_price'=>['required'],
            'rows.*.standard_price' => ['nullable'],
            'rows.*.limit_price' => ['nullable'],
            'rows.*.description' => ['nullable'],
            'rows.*.is_active' => ['nullable'],
            'rows.*.is_default' => ['nullable'],
        ]);
        if (!isset($request['rows']) || count($request['rows']) < 1) {
            return back()->withErrors(['errors' => 'At least one Product price is required.'])->withInput();
        }
        // dd($validator);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        

        $payload['data'] =$data;
        // dd($data);
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
        $activity = Product::where('company_id',auth()->user()->active_company())->find($id);

        return view('product.show', compact('activity','breadcrumbs'));
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
        $activity = Product::where('company_id',auth()->user()->active_company())->find($id);

        return view('product.edit', compact('activity','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $activity
     * *
     */
    public function update(Request $request,$product)
    {
        // Validate the request data
        $product = Product::find($product);
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'sku_no' => ['required'],
			'description' => ['nullable'],
			'product_category_id' => ['required'],
			'product_sub_category_id' => ['required'],
			// 'product_image' => ['nullable'],
			// 'cost_price' => ['required'],
			// 'sale_price' => ['required'],
			'unit' => ['required'],
			'is_default' => ['nullable'],
			'is_active' => ['nullable'],
			
			// 'rma' => ['nullable'],

            // product list validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no'=>['nullable'],
            'rows.*.price_list_version_id'=>['required'],
            'rows.*.list_price'=>['required'],
            'rows.*.standard_price' => ['nullable'],
            'rows.*.limit_price' => ['nullable'],
            'rows.*.description' => ['nullable'],
            'rows.*.is_active' => ['nullable'],
            'rows.*.is_default' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // dd($data);
        $data['updated_by'] = auth()->user()->id;
         // storing partnerlocation of partner
      
         // -----------
        $payload['data']= $data;
        $payload['product']= $product;
        // dd($payload);
        Product::update_product($payload);

        return redirect()->route('products.edit',$product->id)
            ->with('success', 'Price list updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Product::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
    public function deleteActivityRow($id)
    {
        try {
            // Find the row by ID and delete it
            $product = ProductPrice::findOrFail($id);
            if($product->priceList()->exists()){
                // dd('in the main row',$activity_line);
                return response()->json(['error' => true, 'message' => 'you cant remove this row it is associated with product ']);
            }
            else{
                // dd('in the else row',$activity_line);
                $product->delete();
                return response()->json(['success' => true, 'message' => 'Row removed successfully']);
            }


        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove row'], 500);
        }
    }
    public function fetchProductPrice(Request $request)
    {
        // dd($request);
        $productId = $request->input('product_id');
        $product = Product::find($productId);
        $productLine = ProductPrice::where('product_id',$productId)->where('is_active',1)->where('is_default',1)->first();
        // dd($product,$productLine);
        if ($product) {
            return response()->json([
                'success' => true,
                'unit' => $product->unit_measure_id,
                'price' => $productLine->list_price, 
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found',
        ]);
    }

    
}
