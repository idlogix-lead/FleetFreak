<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLine;
use App\Models\Invoice;
use App\Models\InvoiceDocumentType;
use App\Models\MaterialInoutLine;
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
class PurchaseInvoiceController extends Controller
{
    static $ignores = ['deleteActivityRow' => true];
    static $role_module_id = 63;
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
                'name'=>"Purchase Invoice",
                'link'=>route("purchase_invoices.index"),
                'active'=>true,
            ]
        ];

        $perPage = $request->input('perPage', 10);
        $purchase_invoices = Invoice::where('company_id',auth()->user()->active_company())->where('document_type_id',10)->paginate($perPage);

        return view('purchase-invoice.index', compact('purchase_invoices','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $purchase_invoices->perPage());
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
                'name'=>"Purchase Invoice",
                'link'=>route("purchase_invoices.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("purchase_invoices.create"),
                'active'=>true,
            ]
        ];
        $activity = new Invoice();
        return view('purchase-invoice.create', compact('activity','breadcrumbs'));
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
			// 'date_ordered' => ['required'],
			'date_invoiced' => ['nullable'],
			// 'account_date' => ['nullable'],
			'business_partner_id' => ['required'],
			'material_inout_id' => ['required'],
			'order_id' => ['required'],
			// 'invoice_partner_id' => ['required'],
			// 'warehouse_id' => ['required'],
			'price_list_id' => ['nullable'],
			// 'payment_term' => ['nullable'],
			// 'payment_rule' => ['nullable'],
			'discount_printed' => ['nullable'],
			'currency' => ['nullable'],
			'document_action' => ['nullable'],
			'total_amount' => ['required'],
			'grand_total_amount' => ['nullable'],

            // po_Line validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no' => ['nullable'],
            'rows.*.order_detail_line_id'=>['nullable'],
            'rows.*.material_inout_line_id'=>['nullable'],
            'rows.*.quantity'=>['required'],
            'rows.*.product_id'=>['required'],
            'rows.*.unit'=>['required'],
            // 'rows.*.quantity_invoiced'=>['nullable'],
            'rows.*.rate'=>['nullable'],
            // 'rows.*.unit_rate'=>['nullable'],
            // 'rows.*.list_rate'=>['nullable'],
            'rows.*.tax'=>['nullable'],
            'rows.*.tax_amount'=>['nullable'],
            'rows.*.line_amount'=>['required'],
            'rows.*.total_line_amount'=>['required'],
        ]);
        if (!isset($request['rows']) || count($request['rows']) < 1) {
            return back()->withErrors(['errors' => 'At least one Purchase Invoice line is required.'])->withInput();
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
        // -----------
        $data['document_type_id'] = 10;
        $company_id = auth()->user()->active_company();
        $document_id = InvoiceDocumentType::find($data['document_type_id']);
        $document_no = Invoice::generate_no($company_id, $document_id);

        $payload['data'] =$data;
        $payload['document_no'] = $document_no;
        // dd($data);
        Invoice::store_purchase_invoice($payload);

        return redirect()->route('purchase_invoices.index')->with('success', 'Purchase order created successfully.');
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
                'name'=>"Purchase Invoice",
                'link'=>route("purchase_invoices.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("purchase_invoices.show",$id),
                'active'=>true,
            ]
        ];
        $activity = Invoice::where('company_id',auth()->user()->active_company())->find($id);

        return view('purchase-invoice.show', compact('activity','breadcrumbs'));
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
                'name'=>"Purchase Invoice",
                'link'=>route("purchase_invoices.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("purchase_invoices.edit",$id),
                'active'=>true,
            ]
        ];
        $activity = Invoice::where('company_id',auth()->user()->active_company())->find($id);

        return view('purchase-invoice.edit', compact('activity','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Invoice $activity
     * *
     */
    public function update(Request $request,$purchaseInvoice)
    {
        // Validate the request data
        $purchaseInvoice = Invoice::find($purchaseInvoice);
        $validator = Validator::make($request->all(), [
           'document_no' => ['nullable'],
			'document_status' => ['nullable'],
			'description' => ['nullable'],
			// 'date_ordered' => ['required'],
			'date_invoiced' => ['required'],
			// 'account_date' => ['nullable'],
			'business_partner_id' => ['required'],
			'material_inout_id' => ['required'],
			'order_id' => ['nullable'],
			// 'invoice_partner_id' => ['required'],
			// 'warehouse_id' => ['required'],
			'price_list_id' => ['nullable'],
			// 'payment_term' => ['nullable'],
			// 'payment_rule' => ['nullable'],
			'discount_printed' => ['nullable'],
			'currency' => ['nullable'],
			'document_action' => ['nullable'],
			'total_amount' => ['required'],
			'grand_total_amount' => ['nullable'],

            // po_Line validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no' => ['nullable'],
            'rows.*.order_detail_line_id_'=>['nullable'],
            'rows.*.material_inout_line_id_'=>['nullable'],
            'rows.*.quantity'=>['required'],
            'rows.*.product_id'=>['required'],
            'rows.*.unit'=>['required'],
            // 'rows.*.quantity_invoiced'=>['nullable'],
            'rows.*.rate'=>['nullable'],
            // 'rows.*.unit_rate'=>['nullable'],
            // 'rows.*.list_rate'=>['nullable'],
            'rows.*.tax'=>['nullable'],
            'rows.*.tax_amount'=>['nullable'],
            'rows.*.line_amount'=>['required'],
            'rows.*.total_line_amount'=>['required'],
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
         // -----------
        $payload['data']= $data;
        $payload['purchaseInvoice']= $purchaseInvoice;
        Invoice::update_purchase_invoice($payload);

        return redirect()->route('purchase_invoices.edit',$purchaseInvoice->id)
            ->with('success', 'Purchase Invoice updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $activity = Invoice::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('purchase_invoices.index')
            ->with('success', 'Purchase Invoice deleted successfully');
    }
    public function deleteActivityRow($id)
    {
        try {
            // Find the row by ID and delete it
            $purchaseInvoice_line = OrderDetail::findOrFail($id);
            if($purchaseInvoice_line->invoiceLines()->exists()){
                // dd('in the main row',$activity_line);
                return response()->json(['error' => true, 'message' => 'you cant remove this row it is associated with purchase order ']);
            }
            else{
                // dd('in the else row',$activity_line);
                $purchaseInvoice_line->delete();
                return response()->json(['success' => true, 'message' => 'Row removed successfully']);
            }


        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove row'], 500);
        }
    }
    public function checkAvailableQuantity(Request $request)
    {
        $materialLineId = $request->material_line_id;
        $materialline = MaterialInoutLine::find($materialLineId);

        if (!$materialline) {
            return response()->json(['error' => 'Order detail not found'], 404);
        }

        $availableQuantity = $materialline->quantity;

        return response()->json(['available_quantity' => $availableQuantity]);
    }
}
