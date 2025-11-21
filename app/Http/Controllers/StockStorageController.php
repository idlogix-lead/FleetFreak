<?php

namespace App\Http\Controllers;

use App\Models\StockStorage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class StockStorageController
 * @package App\Http\Controllers
 */
class StockStorageController extends Controller
{
    static $ignores = ['deleteActivityRow' => true];
    static $role_module_id = 62;
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
                'name'=>"StockStorage",
                'link'=>route("stock-storages.index"),
                'active'=>true,
            ]
        ];
        $stockStorages = StockStorage::where('company_id',auth()->user()->active_company())->paginate();


        return view('stock-storage.index', compact('stockStorages','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $stockStorages->perPage());
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
                'name'=>"StockStorage",
                'link'=>route("stock-storages.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("stock-storages.create"),
                'active'=>true,
            ]
        ];
        $stockStorage = new StockStorage();
        return view('stock-storage.create', compact('stockStorage','breadcrumbs'));
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
            [
                'product_id'=>'required',
                'locator_id'=>'required',
                'on_hand_qty'=>'nullable',
                'description' => 'nullable',
                'is_active' => 'nullable',
                'is_default' => 'nullable',
        ]
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $payload = [];
        $payload['data'] = $data;
        StockStorage::store_stock_storage($payload);

        return redirect()->route('stock-storages.index')->with('success', 'StockStorage created successfully.');
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
                'name'=>"StockStorage",
                'link'=>route("stock-storages.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("stock-storages.show",$id),
                'active'=>true,
            ]
        ];
        $stockStorage = StockStorage::where('company_id',auth()->user()->active_company())->find($id);

        return view('stock-storage.show', compact('stockStorage','breadcrumbs'));
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
                'name'=>"StockStorage",
                'link'=>route("stock-storages.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("stock-storages.edit",$id),
                'active'=>true,
            ]
        ];
        $stockStorage = StockStorage::where('company_id',auth()->user()->active_company())->find($id);

        return view('stock-storage.edit', compact('stockStorage','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  StockStorage $stockStorage
     * *
     */
    public function update(Request $request,$stockStorage)
    {
        $stockStorage = StockStorage::findOrFail($stockStorage);
        // Validate the request data
        $validator = Validator::make($request->all(),[
            [
                'product_id'=>'required',
                'locator_id'=>'required',
                'on_hand_qty'=>'nullable',
                'description' => 'nullable',
                'is_active' => 'nullable',
                'is_default' => 'nullable',
        ]
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $payload = [];
        $payload['data'] = $data;
        $payload['stockStorage']= $stockStorage;
        StockStorage::update_stock_storage($payload);

        $stockStorage->update($data);

        return redirect()->route('stock-storages.index')
            ->with('success', 'StockStorage updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $stockStorage = StockStorage::find($id)->delete();

        return redirect()->route('stock-storages.index')
            ->with('success', 'StockStorage deleted successfully');
    }
}
