<?php

namespace App\Http\Controllers;

use App\Models\InventoryConsumption;
use App\Models\InventoryMove;
use App\Models\Invoice;
use App\Models\InvoiceDocumentType;
use App\Models\MaterialInout;
use App\Models\MaterialInoutLine;
use App\Models\MovementLine;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PartnerLocation;
use App\Models\PriceList;
use App\Models\PriceListVersion;
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
class InventoryConsumptionController extends Controller
{
    // static $ignores = ['deleteActivityRow' => true,'fetchPoLines'=>true,'fetchPurchaseOrders'=>true];
    static $ignores = ['deleteActivityRow' => true,];
    static $role_module_id = 61;
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
                'name'=>"Inventory Consumption",
                'link'=>route("inventory_consumptions.index"),
                'active'=>true,
            ]
        ];

        $perPage = $request->input('perPage', 10);
        $inventory_consumptions = InventoryConsumption::where('company_id',auth()->user()->active_company())->paginate($perPage);

        return view('inventory-consumption.index', compact('inventory_consumptions','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $inventory_consumptions->perPage());
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
                'name'=>"Inventory Consumption",
                'link'=>route("inventory_consumptions.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("inventory_consumptions.create"),
                'active'=>true,
            ]
        ];
        $activity = new InventoryConsumption();
        return view('inventory-consumption.create', compact('activity','breadcrumbs'));
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
			
			'document_no' => ['nullable'],
			'document_status' => ['nullable'],
			'description' => ['nullable'],
			'movement_date' => ['required'],
			'warehouse_id' => ['required'],
			// 'is_default' => ['nullable'],
			'is_active' => ['nullable'],
			
			// 'rma' => ['nullable'],

            // version lines validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no'=>['nullable'],
            'rows.*.product_id'=>['required'],
            'rows.*.locator_id'=>['nullable'],
            'rows.*.account_id' => ['nullable'],
            'rows.*.movement_qty' => ['nullable'],
            'rows.*.is_active' => ['nullable'],
        ]);
        if (!isset($request['rows']) || count($request['rows']) < 1) {
            return back()->withErrors(['errors' => 'At least one movement line is required.'])->withInput();
        }
        // dd($validator);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;;
        $data['document_type_id'] = 9;
        $company_id = auth()->user()->active_company();
        $document_id = InvoiceDocumentType::find($data['document_type_id']);
        $document_no = InventoryConsumption::generate_no($company_id, $document_id);
        

        $payload['data'] =$data;
        $payload['document_no'] =$document_no;
        // dd($data);
        InventoryConsumption::store_inventory_consumptions($payload);

        return redirect()->route('inventory_consumptions.index')->with('success', 'Inventory Consumption created successfully.');
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
                'name'=>"Inventory Consumption",
                'link'=>route("inventory_consumptions.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("inventory_consumptions.show",$id),
                'active'=>true,
            ]
        ];
        $activity = InventoryConsumption::where('company_id',auth()->user()->active_company())->find($id);

        return view('inventory-consumption.show', compact('activity','breadcrumbs'));
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
                'name'=>"Inventory Consumption",
                'link'=>route("inventory_consumptions.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("inventory_consumptions.edit",$id),
                'active'=>true,
            ]
        ];
        $activity = InventoryConsumption::where('company_id',auth()->user()->active_company())->find($id);

        return view('inventory-consumption.edit', compact('activity','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $activity
     * *
     */
    public function update(Request $request,$inventory_consumptions)
    {
        // Validate the request data
        $inventory_consumptions = InventoryConsumption::find($inventory_consumptions);
        $validator = Validator::make($request->all(), [
            'document_no' => ['nullable'],
			'document_status' => ['nullable'],
			'description' => ['nullable'],
			'movement_date' => ['required'],
			'warehouse_id' => ['required'],
			// 'is_default' => ['nullable'],
			'is_active' => ['nullable'],
			
			// 'rma' => ['nullable'],

            // version lines validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no'=>['nullable'],
            'rows.*.product_id'=>['required'],
            'rows.*.locator_id'=>['nullable'],
            'rows.*.account_id' => ['nullable'],
            'rows.*.movement_qty' => ['nullable'],
            'rows.*.is_active' => ['nullable'],
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
        $payload['inventory_consumptions']= $inventory_consumptions;
        // dd($payload);
        InventoryConsumption::update_inventory_consumptions($payload);

        return redirect()->route('inventory_consumptions.edit',$inventory_consumptions->id)
            ->with('success', 'Inventory Consumption Updated Successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        InventoryConsumption::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('inventory_consumptions.index')
            ->with('success', 'Inventory Consumption Deleted Successfully');
    }
    public function deleteActivityRow($id)
    {
        try {
            // Find the row by ID and delete it
            $movement_line = MovementLine::findOrFail($id);
            if($movement_line->priceList()->exists()){
                // dd('in the main row',$activity_line);
                return response()->json(['error' => true, 'message' => 'you cant remove this row it is associated with inventory move ']);
            }
            else{
                // dd('in the else row',$activity_line);
                $movement_line->delete();
                return response()->json(['success' => true, 'message' => 'Row removed successfully']);
            }


        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove row'], 500);
        }
    }

    
}
