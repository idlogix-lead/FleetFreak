<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\InvoiceLineProduct;
use App\Models\Activity;
use App\Models\InvoiceDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class Maintenance
 * @package App\Http\Controllers
 */
class MaintenanceApprovalsController extends Controller
{
    static $ignores = [];
    static $role_module_id = 48;
    public $my_companies;

    function __construct(){
       
        $this->middleware('RolePermissions');
        
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });

    }
    /**
     * Display a listing of the resource.
     *
     * *
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"Maintenance Approvals",
                'link'=>route("maintenance_approvals.index"),
                'active'=>true,
            ]
        ];

        $perPage = $request->input('perPage', 10);
        // company_id
        $maintenances = Invoice::where('document_type_id',1)->whereIn('document_status',['pending','completed','cancelled'])
        ->where('company_id', auth()->user()->active_company())
        ->orderByDesc('id')
        ->paginate($perPage);

        return view('maintenance-approvals.index', compact('maintenances','breadcrumbs'))
        ->with('i', (request()->input('page', 1) - 1) * $maintenances->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    
    public function edit($id)
    {
       
        $breadcrumbs = [
            [
                'name'=>"Maintenance Approvals",
                'link'=>route("maintenance_approvals.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("maintenance_approvals.edit",$id),
                'active'=>true,
            ]
        ];
        
       
        $maintenance = Invoice::where('document_type_id', 1)
        ->where('company_id', auth()->user()->active_company())
        ->with([
            'invoiceLines.invoiceLineProducts',
            'invoiceLines.activityLine'
        ])
        ->where('id',$id)->first();

        if($maintenance->document_status != 'pending'){
            return redirect()->route('maintenance_approvals.index')
            ->with('error', 'You cannot edit completed maintenance');
        }
        $edit = true;
        // dd($maintenance);
        return view('maintenance-approvals.edit', compact('maintenance','edit','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Invoice $invoice
     * *
     */
    // public function update(Request $request, Invoice $invoice)
    // {
    //     // Validate the request data
    //     $validator = Validator::make($request->all(), Invoice::$rules);
    //     if ($validator->fails()) {
    //         return back()->with('errors', $validator->errors());
    //     }
    //     // Update lead attributes with validated data
    //     $data = $validator->validated();
    //     $data['updated_by'] = auth()->user()->id;

    //     $maintenance->update($data);

    //     return redirect()->route('maintenance.index')
    //     ->with('success', 'Invoice updated successfully');
    // }
    public function update(Request $request, $invoice_id)
    {
        // dd($request);
        $company_id = auth()->user()->active_company();
        // dd($invoice_id);
        $invoice = Invoice::where('id', $invoice_id)->where('company_id', $company_id)
        ->first();
       
        // Validate the request data
        $validator = Validator::make($request->all(), [
			
			'document_status' => ['required'],
			
        ]);

        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $data['company_id'] = $company_id;
        $data['document_type_id'] = 1; // for maintenance
        $payload = [];

        $company_id = auth()->user()->active_company();

        $payload['data'] = $data;
        $payload['invoice'] = $invoice;

        // Invoice::createNextMaintenanceEvent($data, 1);
        // dd('out');
        Invoice::update_maintainence_status($payload);

        // $row = $data['row'];
        // unset
        // $document_no = Invoice::generate_document_no($company_id, 'maintenance');
        // dd($data);
        // Invoice::where('id', $invoice->id)->where('company_id', $company_id)->update([
        //     'vehicle_id' => $data['vehicle_id'],
        //     'business_partner_id' => $data['business_partner_id'],
        //     'date' => $data['date'],
        //     'description' => $data['description'],
        //     'total_amount' => $data['total_amount'],
        //     'grand_total_amount' => $data['grand_total_amount'],
        //     'updated_by' => $data['updated_by'],

        //     'document_status' => $data['document_status'],

        //     // 'company_id' => $company_id,
        //     // 'document_no' => $document_no,
        // ]);
        // foreach($data['row'] as $row){
        //     if($row['row_id']){
        //         InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)->update([
        //             // 'invoice_id' => $maintenance->id,
        //             'is_checked' => $row['is_checked']??null,
        //             'activity_id' => $row['activity_id']??null,
        //             'is_service_charge' => $row['is_service_charge'],
        //             'description' => $row['description'],
        //             'quantity' => $row['quantity'],
        //             'rate' => $row['rate'],
        //             'line_amount' => $row['line_total'],
        //         ]);
        //         // if(isset($row['product_id'])){
        //             InvoiceLineProduct::where('invoice_line_id', $row['row_id'])
        //             // ->where('product_id','!=', $row['product_id'])
        //             ->delete();
        //         // }
        //         $invoice_line_id = $row['row_id'];

        //         if($data['document_status'] == 'completed'){
        //             InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)
        //             ->where('is_checked', 0)
        //             ->delete();
        //             continue;
        //         }
        //     }else{
        //         $invoice_line = InvoiceLine::create([
        //             'invoice_id' => $invoice->id,
        //             'is_checked' => $row['is_checked']??null,
        //             'activity_id' => $row['activity_id']??null,
        //             'is_service_charge' => $row['is_service_charge'],
        //             'description' => $row['description'],
        //             'quantity' => $row['quantity'],
        //             'rate' => $row['rate'],
        //             'line_amount' => $row['line_total'],
        //         ]);
        //         $invoice_line_id = $invoice_line->id;
        //     }
        //     if(isset($row['product_id'])){
        //         InvoiceLineProduct::firstOrCreate([
        //             'invoice_line_id' => $invoice_line_id,
        //             'product_id' => $row['product_id'],
        //         ]);
        //     }
        //     // foreach($row['product_id']??[] as $product_id){
        //     //     InvoiceLineProduct::firstOrCreate([
        //     //         'invoice_line_id' => $invoice_line_id,
        //     //         'product_id' => $product_id,
        //     //     ]);
        //     // }
        // }
        // for haris-------:
      
        //----------
        return redirect()->route("maintenance_approvals.index")->with('success',' Maintenance Updated successfully.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        return redirect()->route('maintenance_approvals.index')
        ->with('error', 'You cannot delete maintenance');
        $company_id = auth()->user()->active_company();
        $maintenance = Invoice::where('id',$id)->where('company_id', $company_id)
        ->where('document_type_id', ($is_inspection?5:1))
        ->where('document_status', 'draft')
        ->delete();

        return redirect()->back()
            ->with('success', ($is_inspection?"Inspection":"Maintenance").' deleted successfully');
    }

    public function destroy_row($invoice_line_id){
        $is_inspection = $this->is_inspection;
        $company_id = auth()->user()->active_company();
        $check = InvoiceLine::where('id', $invoice_line_id)
        ->whereHas('invoice', function($invoice) use($company_id, $is_inspection){
            return $invoice->where('company_id', $company_id)
            ->where('document_type_id', ($is_inspection?5:1))
            ->where('document_status', 'draft');
        })
        ->where('is_checked', null)
        ->where('is_service_charge', 0)
        ->first();
        if($check){
            InvoiceLine::find($check->id)->delete();
            return response()->json(['success'=>'Deleted Successfully'], 200);
        }else{
            return response()->json(['success'=>'Sorry You cannot Delete this Row'], 401);
        }
    }
}
