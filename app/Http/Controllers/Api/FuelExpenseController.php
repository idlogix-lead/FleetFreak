<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\InvoiceLineProduct;
use App\Models\Activity;
use App\Models\InvoiceDocumentType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
class FuelExpenseController extends Controller
{
    static $role_module_id = 38;
    public $my_companies;

     static $ignores = [
        // 'api_index' => true,
        // 'api_store' => true,
        // 'api_show' =>true,
        // 'api_update' => true,
        // 'api_destroy' => true,
        // 'api_edit'=>true
    ];
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        // $this->middleware('RolePermissions');
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function api_index()
    {
        //
        $breadcrumbs = [
            [
                'name'=>"FuelExpense",
                'link'=>route("fuel-expenses.index"),
                'active'=>true,
            ]
        ];
            // dd('kjlk ');
                // company_id
        $fuelExpenses = Invoice::where('document_type_id', '3')
            ->where('company_id', auth()->user()->active_company())
            ->orderByDesc('id')
            ->paginate();

        //    dd($tollTaxes);
        return response()->json(['message' => 'success', 'fuel-expenses' => $fuelExpenses, 'breadcrumbss' => $breadcrumbs]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function api_store(Request $request)
    {
              // dd($request);
        // Validate the request data
        $validator = Validator::make($request->all(), [
            // 'document_no' => ['required'],
            // 'company_id' => ['required'],
            'vehicle_id' => ['required'],
            'business_partner_id' => ['required'],
            'date' => ['required'],
            'description' => ['nullable', 'string'],
            'total_amount' => ['required'],
            'grand_total_amount' => ['required'],
            'document_status' => ['required'],
            // 'document_type' => ['required'],
            // 'document_type_id' => ['required'],

            'row' => ['required', 'array'],
            'row.*.line_amount' => ['required'],
            'row.*.meter_reading_km' => ['required'],
            'row.*.fuel_quantity_liters' => ['required'],
            'row.*.meter_reading_image' => ['required'],
            'row.*.total_fuel_cost' => ['required'],
            'row.*.bill_image' => ['required'],
            'row.*.petrol_machine_image' => ['required'],
            'row.*.driver_selfie' => ['nullable'],

            // 'row.*.line_amount' => ['required'],
            // 'row.*.check_point' => ['required'],
            // 'row.*.picture_of_toll_tax' => ['required'],
            // 'row.*.is_checked' => ['nullable'],
            // 'row.*.activity_id' => ['nullable'],
            // 'row.*.is_service_charge' => ['required'],
            // 'row.*.description' => ['nullable'],
            // 'row.*.quantity' => ['required'],
            // 'row.*.rate' => ['required'],
            // 'row.*.line_total' => ['required'],
            // 'row.*.product_id' => ['nullable'],
            // 'row.*.product_id.*' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // dd($data);
        $data['created_by'] = auth()->user()->id;
        // $row = $data['row'];
        // unset
        $company_id = auth()->user()->active_company();
        $data['document_type_id'] = 3; // for tolltax
        $document_id = InvoiceDocumentType::find($data['document_type_id']);
        // $document_no = Invoice::generate_document_no($company_id, 'maintenance');
        $document_no = Invoice::generate_document_no1($company_id, $document_id);
        // dd($data);
        $payload = [];
        $payload['data'] = $data;
        $payload['document_no'] = $document_no;
        Invoice::store_fuel_expense($payload);
        return response()->json(['success' => 'Fuel expense invoice created successfully','data' => $payload]);
    }

    /**
     * Display the specified resource.
     */
    public function api_show(string $id)
    {
        //
        $breadcrumbs = [
            [
                'name'=>"FuelExpense",
                'link'=>route("fuel-expenses.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("fuel-expenses.show",$id),
                'active'=>true,
            ]
        ];
        $fuelExpense = Invoice::find($id);
        if($fuelExpense==null){
            return response()->json(['message'=>"Fuel-Expense invoice not found"]);
        }
        // return response()->json(['data' => $tolltax]);
        return response()->json(['message' => 'success', 'fuel-expense' => $fuelExpense, 'breadcrumbss' => $breadcrumbs]);

    }

     public function api_edit(string $id)
    {
        //
         $breadcrumbs = [
            [
                'name'=>"FuelExpense",
                'link'=>route("fuel-expenses.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("fuel-expenses.edit",$id),
                'active'=>true,
            ]
        ];

        $fuelExpense = Invoice::where('document_type_id', '3')
        ->where('company_id', auth()->user()->active_company())
        ->with([
            'invoiceLines.invoiceLineProducts',
            'invoiceLines.activityLine'
        ])
        ->where('id', $id)->first();
        if($fuelExpense==null){
            return response()->json(['message'=>'Fuel Expense invoice not found']);
        }
        $edit = true;

        return response()->json(['message' => 'success', 'fuel-expense' => $fuelExpense,'edit'=>$edit, 'breadcrumbss' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function api_update(Request $request,  $invoice_id)
    {
        //

         // dd("knktgnk");
        // Validate the request data
         $company_id = auth()->user()->active_company();
        // dd($invoice_id);
        $invoice = Invoice::where('id', $invoice_id)->where('company_id', $company_id)
            ->first();
        if ($invoice->document_status != "draft") {
             return response()->json(['message' =>'With This Status You can not Edit the document']);
        }

      $data = $request->all();

        // Fields to check for existing images
        $imageFields = ['meter_reading_image', 'bill_image', 'petrol_machine_image', 'driver_selfie'];

        if (isset($data['row']) && is_array($data['row'])) {
            foreach ($data['row'] as $index => $row) {
                foreach ($imageFields as $field) {
                    $existingField = "{$field}_existing";
                    if (empty($row[$field]) && !empty($row[$existingField])) {
                        $data['row'][$index][$field] = $row[$existingField];
                    }
                }
            }
        }


        // Validate the request data
        $validator = Validator::make($data, [
            // 'document_no' => ['required'],
            // 'company_id' => ['required'],
            'vehicle_id' => ['required'],
            'business_partner_id' => ['required'],
            'date' => ['required'],
            'description' => ['nullable', 'string'],
            'total_amount' => ['required'],
            'grand_total_amount' => ['required'],
            'document_status' => ['required'],
            // 'document_type' => ['required'],
            'row' => ['required', 'array'],
            'row.*.line_amount' => ['required'],
            'row.*.meter_reading_km' => ['required'],
            'row.*.fuel_quantity_liters' => ['required'],
            'row.*.total_fuel_cost' => ['required'],
            'row.*.meter_reading_image' => ['required'],
            'row.*.bill_image' => ['required'],
            'row.*.petrol_machine_image' => ['required'],
            'row.*.driver_selfie' => ['nullable'],

            // 'row.*.line_amount' => ['required'],
            // 'row.*.check_point' => ['required'],
            // 'row.*.picture_of_toll_tax' => ['required'],
            // 'row.*.is_checked' => ['nullable'],
            // 'row.*.activity_id' => ['nullable'],
            // 'row.*.is_service_charge' => ['required'],
            // 'row.*.description' => ['nullable'],
            // 'row.*.quantity' => ['required'],
            // 'row.*.rate' => ['required'],
            // 'row.*.line_total' => ['required'],
            // 'row.*.product_id' => ['nullable'],
            // 'row.*.product_id.*' => ['required'],
        ]);

        if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()], 400);

        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $payload = [];

        $payload['data'] = $data;
        $payload['invoice'] = $invoice;
        Invoice::update_fuel_expense($payload);
        $invoice = Invoice::where('id', $invoice_id)->where('company_id', $company_id)
            ->first();
        if ($data['document_status'] == 'completed') {
        }

        return response()->json(['message' => 'Fuel Expense Invoice Updated successfully.', 'updated-fuel-expense' => $invoice]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function api_destroy(string $id)
    {
        $company_id = auth()->user()->active_company();

            // Find the invoice
            $invoice = Invoice::where('id', $id)->where('company_id', $company_id)->first();

            // Check if the invoice exists
            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found.'], 404);
            }

            // Check if the document status is not "draft"
            if ($invoice->document_status != "draft") {
                return response()->json(['message' => 'With this status, you cannot delete the document.']);
            }

            // Attempt to delete the Toll Tax invoice
            $tolltax = Invoice::where('id', $id)
                ->where('company_id', $company_id)
                ->where('document_type_id', '3')
                ->where('document_status', 'draft')
                ->delete();

            // Check if deletion was successful
            if ($tolltax > 0) {
                return response()->json(['message' => 'Fuel Expense Invoice deleted successfully.']);
            } else {
                return response()->json(['message' => 'Fuel Expense invoice with this id not found.']);
            }
    }
}
