<?php

namespace App\Http\Controllers;

use App\Models\ProductPrice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ProductPriceController
 * @package App\Http\Controllers
 */
class ProductPriceController extends Controller
{
    static $role_module_id = 59;
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
                'name'=>"ProductPrice",
                'link'=>route("product-prices.index"),
                'active'=>true,
            ]
        ];
        $productPrices = ProductPrice::paginate();


        return view('product-price.index', compact('productPrices','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $productPrices->perPage());
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
                'name'=>"ProductPrice",
                'link'=>route("product-prices.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("product-prices.create"),
                'active'=>true,
            ]
        ];
        $productPrice = new ProductPrice();
        return view('product-price.create', compact('productPrice','breadcrumbs'));
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
        $validator = Validator::make($request->all(), ProductPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $productPrice = ProductPrice::create($data);

        return redirect()->route('product-prices.index')->with('success', 'ProductPrice created successfully.');
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
                'name'=>"ProductPrice",
                'link'=>route("product-prices.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("product-prices.show",$id),
                'active'=>true,
            ]
        ];
        $productPrice = ProductPrice::find($id);

        return view('product-price.show', compact('productPrice','breadcrumbs'));
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
                'name'=>"ProductPrice",
                'link'=>route("product-prices.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("product-prices.edit",$id),
                'active'=>true,
            ]
        ];
        $productPrice = ProductPrice::find($id);

        return view('product-price.edit', compact('productPrice','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ProductPrice $productPrice
     * *
     */
    public function update(Request $request, ProductPrice $productPrice)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), ProductPrice::$rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $productPrice->update($data);

        return redirect()->route('product-prices.index')
            ->with('success', 'ProductPrice updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $productPrice = ProductPrice::find($id)->delete();

        return redirect()->route('product-prices.index')
            ->with('success', 'ProductPrice deleted successfully');
    }
}
