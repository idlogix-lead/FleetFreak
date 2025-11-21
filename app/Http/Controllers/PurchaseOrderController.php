<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLine;
use App\Models\Invoice;
use App\Models\InvoiceDocumentType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PartnerLocation;
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
class PurchaseOrderController extends Controller
{
    static $ignores = ['deleteActivityRow' => true,'printPurchaseOrder'=>true];
    static $role_module_id = 57;
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
                'name'=>"Purchase Order",
                'link'=>route("purchase_orders.index"),
                'active'=>true,
            ]
        ];

        $perPage = $request->input('perPage', 10);
        $purchase_orders = Order::where('company_id',auth()->user()->active_company())->where('document_type_id',6)->paginate($perPage);

        return view('purchase-order.index', compact('purchase_orders','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $purchase_orders->perPage());
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
                'name'=>"Purchase Order",
                'link'=>route("purchase_orders.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("purchase_orders.create"),
                'active'=>true,
            ]
        ];
        $activity = new Order();
        return view('purchase-order.create', compact('activity','breadcrumbs'));
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
			'po_reference' => ['nullable'],
			'date_ordered' => ['required'],
			'date_promised' => ['required'],
			'business_partner_id' => ['required'],
			// 'invoice_partner_id' => ['nullable'],
			'warehouse_id' => ['required'],
			'price_list' => ['nullable'],
			'currency' => ['nullable'],
			'payment_term' => ['nullable'],
			'document_action' => ['nullable'],
			'booking_amount' => ['required'],
			'final_amount' => ['nullable'],

            // po_Line validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.date_ordered'=>['nullable'],
            'rows.*.date_promised'=>['nullable'],
            'rows.*.quantity'=>['required'],
            'rows.*.product_id'=>['required'],
            'rows.*.unit'=>['required'],
            'rows.*.order_qty'=>['nullable'],
            'rows.*.delivered_qty'=>['nullable'],
            'rows.*.reserved_qty'=>['nullable'],
            'rows.*.invoiced_qty'=>['nullable'],
            'rows.*.rate'=>['required'],
            'rows.*.unit_price'=>['nullable'],
            'rows.*.list_price'=>['nullable'],
            'rows.*.tax'=>['nullable'],
            'rows.*.discount'=>['nullable'],
            'rows.*.tax_value'=>['nullable'],
            'rows.*.line_amount'=>['required'],
            'rows.*.total_line_amount'=>['required'],
            'rows.*.seq_no' => ['nullable'],
        ]);
        if (!isset($request['rows']) || count($request['rows']) < 1) {
            return back()->withErrors(['errors' => 'At least one Purchase order line is required.'])->withInput();
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
        // $invoice_location_id = PartnerLocation::where('partner_id',$data['invoice_partner_id'])->where('is_default',1)->value('id');
        $data['partner_location_id'] = $partner_location_id;
        // $data['invoice_location_id'] = $invoice_location_id;
        // -----------
        $data['document_type_id'] = 6;
        $company_id = auth()->user()->active_company();
        $document_id = InvoiceDocumentType::find($data['document_type_id']);
        $document_no = Order::generate_PO_no($company_id, $document_id);

        $payload['data'] =$data;
        $payload['document_no'] = $document_no;
        // dd($data);
        Order::store_purchase_order($payload);

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order created successfully.');
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
                'name'=>"Purchase Order",
                'link'=>route("purchase_orders.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("purchase_orders.show",$id),
                'active'=>true,
            ]
        ];
        $activity = Order::where('company_id',auth()->user()->active_company())->find($id);

        return view('purchase-order.show', compact('activity','breadcrumbs'));
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
                'name'=>"Purchase Order",
                'link'=>route("purchase_orders.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("purchase_orders.edit",$id),
                'active'=>true,
            ]
        ];
        $activity = Order::where('company_id',auth()->user()->active_company())->find($id);

        return view('purchase-order.edit', compact('activity','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $activity
     * *
     */
    public function update(Request $request,$purchaseOrder)
    {
        // Validate the request data
        // dd($request);
        $purchaseOrder = Order::find($purchaseOrder);
        $validator = Validator::make($request->all(), [
            'document_no' => ['nullable'],
			'document_status' => ['nullable'],
			'description' => ['nullable'],
			'po_reference' => ['nullable'],
			'date_ordered' => ['required'],
			'date_promised' => ['required'],
			'business_partner_id' => ['required'],
			// 'invoice_partner_id' => ['nullable'],
			'warehouse_id' => ['required'],
			'price_list' => ['nullable'],
			'currency' => ['nullable'],
			'payment_term' => ['nullable'],
			'document_action' => ['nullable'],
			'booking_amount' => ['required'],
			'final_amount' => ['nullable'],

            // po_Line validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.date_ordered'=>['nullable'],
            'rows.*.date_promised'=>['nullable'],
            'rows.*.quantity'=>['required'],
            'rows.*.product_id'=>['required'],
            'rows.*.unit'=>['required'],
            'rows.*.order_qty'=>['nullable'],
            'rows.*.delivered_qty'=>['nullable'],
            'rows.*.reserved_qty'=>['nullable'],
            'rows.*.invoiced_qty'=>['nullable'],
            'rows.*.rate'=>['required'],
            'rows.*.unit_price'=>['nullable'],
            'rows.*.list_price'=>['nullable'],
            'rows.*.tax'=>['nullable'],
            'rows.*.discount'=>['nullable'],
            'rows.*.tax_value'=>['nullable'],
            'rows.*.line_amount'=>['required'],
            'rows.*.total_line_amount'=>['required'],
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
        //  $invoice_location_id = PartnerLocation::where('partner_id',$data['invoice_partner_id'])->where('is_default',1)->value('id');
         $data['partner_location_id'] = $partner_location_id;
        //  $data['invoice_location_id'] = $invoice_location_id;
         // -----------
        $payload['data']= $data;
        $payload['purchaseOrder']= $purchaseOrder;
        Order::update_purchase_order($payload);

        return redirect()->route('purchase_orders.edit',$purchaseOrder->id)
            ->with('success', 'Purchase Order updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $activity = Order::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('purchase_orders.index')
            ->with('success', 'Purchase Order deleted successfully');
    }
    public function deleteActivityRow($id)
    {
        try {
            // Find the row by ID and delete it
            $purchaseOrder_line = OrderDetail::findOrFail($id);
            if($purchaseOrder_line->invoiceLines()->exists()){
                // dd('in the main row',$activity_line);
                return response()->json(['error' => true, 'message' => 'you cant remove this row it is associated with purchase order ']);
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

    public function printPurchaseOrder($id)
    {
        // dd($id);
        $order = Order::findOrFail($id);
        $order_details = OrderDetail::where('order_id',$order->id)->get();
        return view('print.print_purchase_orders', compact('order','order_details'));
    }
}
