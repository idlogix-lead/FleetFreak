<?php

namespace App\Http\Controllers;

use App\Models\ProductCosting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ProductCostingController
 * @package App\Http\Controllers
 */
class ProductCostingController extends Controller
{
    static $ignores = ['deleteActivityRow' => true,'fetchProductPrice'=>true];
    static $role_module_id = 65;
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
                'name'=>"ProductCosting",
                'link'=>route("product-costings.index"),
                'active'=>true,
            ]
        ];
        $productCostings = ProductCosting::where('company_id',auth()->user()->active_company())->paginate();


        return view('product-costing.index', compact('productCostings','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $productCostings->perPage());
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
                'name'=>"ProductCosting",
                'link'=>route("product-costings.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("product-costings.create"),
                'active'=>true,
            ]
        ];
        $productCosting = new ProductCosting();
        return view('product-costing.create', compact('productCosting','breadcrumbs'));
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
        $validator = Validator::make($request->all(), ProductCosting::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $productCosting = ProductCosting::create($data);

        return redirect()->route('product-costings.index')->with('success', 'ProductCosting created successfully.');
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
                'name'=>"ProductCosting",
                'link'=>route("product-costings.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("product-costings.show",$id),
                'active'=>true,
            ]
        ];
        $productCosting = ProductCosting::where('company_id',auth()->user()->active_company())->find($id);

        return view('product-costing.show', compact('productCosting','breadcrumbs'));
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
                'name'=>"ProductCosting",
                'link'=>route("product-costings.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("product-costings.edit",$id),
                'active'=>true,
            ]
        ];
        $productCosting = ProductCosting::where('company_id',auth()->user()->active_company())->find($id);

        return view('product-costing.edit', compact('productCosting','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ProductCosting $productCosting
     * *
     */
    public function update(Request $request, ProductCosting $productCosting)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), ProductCosting::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $productCosting->update($data);

        return redirect()->route('product-costings.index')
            ->with('success', 'ProductCosting updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $productCosting = ProductCosting::find($id)->delete();

        return redirect()->route('product-costings.index')
            ->with('success', 'ProductCosting deleted successfully');
    }
}
