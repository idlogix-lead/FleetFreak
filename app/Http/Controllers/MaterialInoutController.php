<?php

namespace App\Http\Controllers;


use App\Models\Invoice;
use App\Models\InvoiceDocumentType;
use App\Models\Locator;
use App\Models\MaterialInout;
use App\Models\MaterialInoutLine;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PartnerLocation;
use App\Rules\ValidPoQuantity;
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
class MaterialInoutController extends Controller
{
    // static $ignores = ['deleteActivityRow' => true,'fetchPoLines'=>true,'fetchPurchaseOrders'=>true];
    static $ignores = ['getPurchaseOrderDate'=>true,'fetchReceipt'=>true,'fetchMaterialLines'=>true,'getReceiptOrderId'=>true,'getLocators'=>true];
    static $role_module_id = 58;
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
                'name'=>"Material Receipt",
                'link'=>route("material_inout.index"),
                'active'=>true,
            ]
        ];

        $perPage = $request->input('perPage', 10);
        $material_inouts = MaterialInout::where('company_id',auth()->user()->active_company())->paginate($perPage);

        return view('material-inout.index', compact('material_inouts','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $material_inouts->perPage());
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
                'name'=>"Material Receipt",
                'link'=>route("material_inout.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("material_inout.create"),
                'active'=>true,
            ]
        ];
        $activity = new MaterialInout();
        return view('material-inout.create', compact('activity','breadcrumbs'));
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
			'order_id' => ['required'],
			'document_status' => ['nullable'],
			'description' => ['nullable'],
			'po_reference' => ['nullable'],
			'date_ordered' => ['required'],
			'movement_date' => ['required'],
			// 'accounting_date' => ['required'],
			'business_partner_id' => ['required'],
			'warehouse_id' => ['required'],
			'delivery_man' => ['nullable'],
			'delivery_vehicle' => ['nullable'],
			'delivery_no' => ['nullable'],
			'delivery_time' => ['required'],
			// 'gate_inout' => ['nullable'],
			// 'create_lines_from' => ['nullable'],
			// 'c_l_from_gatepass' => ['nullable'],
			'document_action' => ['nullable'],
			// 'rma' => ['nullable'],

            // material_Line validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no'=>['nullable'],
            'rows.*.order_detail_line_id'=>['nullable'],
            'rows.*.product_id'=>['required'],
            'rows.*.locator_id'=>['required'],
            // 'rows.*.description'=>['nullable'],
            'rows.*.quantity'=>['required', 'numeric', new ValidPoQuantity()],
            'rows.*.movement_qty'=>['nullable'],
            'rows.*.picked_qty'=>['nullable'],
            'rows.*.target_qty'=>['nullable'],
            'rows.*.confirmed_qty'=>['nullable'],
            'rows.*.scrapped_qty'=>['nullable'],
            'rows.*.seq_no' => ['nullable'],
        ]);
        if (!isset($request['rows']) || count($request['rows']) < 1) {
            return back()->withErrors(['errors' => 'At least one Material Inout line is required.'])->withInput();
        }
        // dd($validator);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        

        // storing partnerlocation of partner
        $partner_location_id = PartnerLocation::where('partner_id',$data['business_partner_id'])->where('is_default',1)->value('id');
        $data['partner_location_id'] = $partner_location_id;
        // -----------
        $data['document_type_id'] = 7;
        $company_id = auth()->user()->active_company();
        $document_id = InvoiceDocumentType::find($data['document_type_id']);
        $document_no = MaterialInout::generate_document_no2($company_id, $document_id);

        $payload['data'] =$data;
        $payload['document_no'] = $document_no;
        // dd($data);
        MaterialInout::store_material_inout($payload);

        return redirect()->route('material_inout.index')->with('success', 'Material Inout created successfully.');
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
                'name'=>"Material Receipt",
                'link'=>route("material_inout.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("material_inout.show",$id),
                'active'=>true,
            ]
        ];
        $activity = MaterialInout::where('company_id',auth()->user()->active_company())->find($id);

        return view('material-inout.show', compact('activity','breadcrumbs'));
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
                'name'=>"Material Receipt",
                'link'=>route("material_inout.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("material_inout.edit",$id),
                'active'=>true,
            ]
        ];
        $activity = MaterialInout::where('company_id',auth()->user()->active_company())->find($id);

        return view('material-inout.edit', compact('activity','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $activity
     * *
     */
    public function update(Request $request,$material_inout)
    {
        // Validate the request data
        $material_inout = MaterialInout::find($material_inout);
        $validator = Validator::make($request->all(), [
            'document_no' => ['nullable'],
			'document_status' => ['nullable'],
			'description' => ['nullable'],
			'po_reference' => ['nullable'],
			'date_ordered' => ['required'],
			'movement_date' => ['required'],
			// 'accounting_date' => ['required'],
			'business_partner_id' => ['required'],
			'warehouse_id' => ['required'],
			'delivery_man' => ['nullable'],
			'delivery_vehicle' => ['nullable'],
			'delivery_no' => ['nullable'],
			'delivery_time' => ['required'],
			// 'gate_inout' => ['nullable'],
			// 'create_lines_from' => ['nullable'],
			// 'c_l_from_gatepass' => ['nullable'],
			'document_action' => ['nullable'],
			// 'rma' => ['nullable'],


            // po_Line validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.order_detail_line_id'=>['nullable'],
            'rows.*.product_id'=>['required'],
            'rows.*.locator_id'=>['nullable'],
            // 'rows.*.description'=>['nullable'],
            'rows.*.quantity'=>['required'],
            'rows.*.movement_qty'=>['nullable'],
            'rows.*.picked_qty'=>['nullable'],
            'rows.*.target_qty'=>['nullable'],
            'rows.*.confirmed_qty'=>['nullable'],
            'rows.*.scrapped_qty'=>['nullable'],
            'rows.*.seq_no' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // dd($data);
        $data['updated_by'] = auth()->user()->id;
         // storing partnerlocation of partner
         $partner_location_id = PartnerLocation::where('partner_id',$data['business_partner_id'])->where('is_default',1)->value('id');
         $data['partner_location_id'] = $partner_location_id;
         // -----------
        $payload['data']= $data;
        $payload['material_inout']= $material_inout;
        // dd($payload);
        MaterialInout::update_material_inout($payload);

        return redirect()->route('material_inout.edit',$material_inout->id)
            ->with('success', 'Material Inout updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $activity = MaterialInout::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('material_inout.index')
            ->with('success', 'Material Inout deleted successfully');
    }
    public function deleteActivityRow($id)
    {
        try {
            // Find the row by ID and delete it
            $purchaseOrder_line = MaterialInoutLine::findOrFail($id);
            if($purchaseOrder_line->invoiceLines()->exists()){
                // dd('in the main row',$activity_line);
                return response()->json(['error' => true, 'message' => 'you cant remove this row it is associated with material inout ']);
            }
            else{
                // dd('in the else row',$activity_line);
                $purchaseOrder_line->delete();
                return response()->json(['success' => true, 'message' => 'Row removed successfully']);
            }


        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove row'], 500);
        }
    }

    public function fetchPoLines(Request $request)
    {
        // Validate the request
        // $request->validate([
        //     'order_id' => 'required|exists:orders,id',
        // ]);

        // Fetch the order details
        $order = OrderDetail::where('order_id',$request->order_id)->whereHas('order',function($q) use ($request){
            $q->where('business_partner_id',$request->business_partner_id);
        })->get()->toArray();
        // dd($order);
        // Return the order details as JSON
        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }
    function fetchPurchaseOrders(Request $request)
    {
        // Validate request
        // $request->validate([
        //     'business_partner_id' => 'required|exists:partners,id',
        // ]);
    
        // Fetch orders for the selected business partner
        $orders = Order::where('business_partner_id', $request->business_partner_id)->where('document_status','completed')
        ->whereHas('orderDetails',function($q){
                    $q->where('status','pending');
                })->get();
        // dd($orders);
        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
    public function getPurchaseOrderDate(Request $request)
    {
        $purchaseOrder = Order::find($request->order_id);

        if ($purchaseOrder) {
            return response()->json([
                'date_ordered' => $purchaseOrder->date_ordered
            ]);
        }
    
        return response()->json(['date_ordered' => null]);
    }
    function fetchReceipt(Request $request)
    {
        // Validate request
        // $request->validate([
        //     'business_partner_id' => 'required|exists:partners,id',
        // ]);
    
        // Fetch material_inout for the selected business partner
        $material_inout = MaterialInout::with('order')->where('business_partner_id', $request->business_partner_id)->where('document_status','completed')
        ->whereHas('materialInoutline',function($q){
                    $q->where('status','pending');
                })
        ->get();
        // dd($material_inout);
        return response()->json([
            'success' => true,
            'data' => $material_inout,
        ]);
    }
    public function fetchMaterialLines(Request $request)
    {
        // Validate the request
        // $request->validate([
        //     'order_id' => 'required|exists:orders,id',
        // ]);

        // Fetch the order details
        $order = MaterialInoutLine::with(['product','product.unitType','orderLine'])->where('m_inout_id',$request->material_inout_id)->where('status','pending')->whereHas('materialInout',function($q) use ($request){
            $q->where('business_partner_id',$request->business_partner_id);
        })->get()->toArray();
        // dd($order);
        // Return the order details as JSON
        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }
    public function getReceiptOrderId(Request $request)
    {
        $purchaseOrder = MaterialInout::find($request->material_inout_id);
        
        // dd($purchaseOrder);
        if ($purchaseOrder) {
            return response()->json([
                'order_id' => $purchaseOrder->order_id
            ]);
        }
    
        return response()->json(['order_id' => null]);
    }
    function getLocators($warehouseId){
        $locators = Locator::where('warehouse_id', $warehouseId)->get();
        return response()->json(['locators' => $locators]);
    }
    public function checkAvailableQuantity(Request $request)
    {
        $orderDetailId = $request->order_detail_id;
        $orderDetail = OrderDetail::find($orderDetailId);

        if (!$orderDetail) {
            return response()->json(['error' => 'Order detail not found'], 404);
        }

        $availableQuantity = $orderDetail->quantity - $orderDetail->delivered_qty;

        return response()->json(['available_quantity' => $availableQuantity]);
    }
    
}
