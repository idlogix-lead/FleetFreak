<?php

namespace App\Http\Controllers;

use App\Models\PhysicalInventory;
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
class PhysicalInventoryController extends Controller
{
    static $ignores = ['deleteRow' => true,];
    static $role_module_id = 64;
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
                'name'=>"Physical Inventory",
                'link'=>route("physical_inventory.index"),
                'active'=>true,
            ]
        ];

        $perPage = $request->input('perPage', 10);
        $inventory_moves = PhysicalInventory::where('company_id',auth()->user()->active_company())->paginate($perPage);

        return view('physical-inventory.index', compact('inventory_moves','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $inventory_moves->perPage());
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
                'name'=>"Physical Inventory",
                'link'=>route("physical_inventory.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("physical_inventory.create"),
                'active'=>true,
            ]
        ];
        $activity = new PhysicalInventory();
        return view('physical-inventory.create', compact('activity','breadcrumbs'));
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
			'inventory_date' => ['required'],
			'warehouse_id' => ['required'],
			'is_active' => ['nullable'],
			
			// 'rma' => ['nullable'],

            // version lines validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no'=>['nullable'],
            'rows.*.product_id'=>['required'],
            'rows.*.locator_id'=>['required'],
            'rows.*.description' => ['nullable'],
            'rows.*.system_qty' => ['nullable'],
            'rows.*.physical_qty' => ['nullable'],
            'rows.*.adjusted_qty' => ['nullable'],
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
        $data['document_type_id'] = 11;
        $company_id = auth()->user()->active_company();
        $document_id = InvoiceDocumentType::find($data['document_type_id']);
        $document_no = PhysicalInventory::generate_no($company_id, $document_id);
        

        $payload['data'] =$data;
        $payload['document_no'] =$document_no;
        // dd($data);
        PhysicalInventory::store_physical_inventory($payload);

        return redirect()->route('physical_inventory.index')->with('success', 'Physical Inventory created successfully.');
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
                'name'=>"Physical Inventory",
                'link'=>route("physical_inventory.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("physical_inventory.show",$id),
                'active'=>true,
            ]
        ];
        $activity = PhysicalInventory::where('company_id',auth()->user()->active_company())->find($id);

        return view('physical-inventory.show', compact('activity','breadcrumbs'));
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
                'name'=>"Physical Inventory",
                'link'=>route("physical_inventory.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("physical_inventory.edit",$id),
                'active'=>true,
            ]
        ];
        $activity = PhysicalInventory::where('company_id',auth()->user()->active_company())->find($id);

        return view('physical-inventory.edit', compact('activity','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $activity
     * *
     */
    public function update(Request $request,$physical_inventory)
    {
        // Validate the request data
        $physical_inventory = PhysicalInventory::find($physical_inventory);
        $validator = Validator::make($request->all(), [
            'document_no' => ['nullable'],
			'document_status' => ['nullable'],
			'description' => ['nullable'],
			'inventory_date' => ['required'],
			'warehouse_id' => ['required'],
			'is_active' => ['nullable'],
			
            // physical inventory lines validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no'=>['nullable'],
            'rows.*.product_id'=>['required'],
            'rows.*.locator_id'=>['required'],
            'rows.*.description' => ['nullable'],
            'rows.*.system_qty' => ['nullable'],
            'rows.*.physical_qty' => ['nullable'],
            'rows.*.adjusted_qty' => ['nullable'],
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
        $payload['physical_inventory']= $physical_inventory;
        // dd($payload);
        PhysicalInventory::update_physical_inventory($payload);

        return redirect()->route('physical_inventory.edit',$physical_inventory->id)
            ->with('success', 'Physical Inventory Updated Successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        PhysicalInventory::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('physical_inventory.index')
            ->with('success', 'Physical Inventory Deleted Successfully');
    }
    public function deleteRow($id)
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
