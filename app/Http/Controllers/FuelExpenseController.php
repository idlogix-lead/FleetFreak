<?php

namespace App\Http\Controllers;

// use App\Models\FuelExpense;
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
 * Class FuelExpenseController
 * @package App\Http\Controllers
 */
class FuelExpenseController extends Controller
{
     // static $ignores = ['deleteActivityRow' => true];
    static $role_module_id = 38;
    static $ignores = ['destroy_row' => true];

    public $my_companies;

    function __construct()
    {
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
                'name'=>"FuelExpense",
                'link'=>route("fuel-expenses.index"),
                'active'=>true,
            ]
        ];

        $perPage = $request->input('perPage', 10);

                // company_id
        $fuelExpenses = Invoice::where('document_type_id', '3')
            ->where('company_id', auth()->user()->active_company())
            ->orderByDesc('id')
            ->paginate($perPage);

        //    dd($tollTaxes);

        return view('fuel-expense.index', compact('fuelExpenses','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $fuelExpenses->perPage());
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
                'name'=>"FuelExpense",
                'link'=>route("fuel-expenses.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("fuel-expenses.create"),
                'active'=>true,
            ]
        ];

        $fuelExpense = new Invoice();
        $activity = Activity::getActiveActivity(auth()->user()->active_company());
        // dd($activity);
        return view('fuel-expense.create', compact('fuelExpense', 'activity','breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function store(Request $request)
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
            'row.*.meter_reading_image' => ['nullable'],
            'row.*.total_fuel_cost' => ['required'],
            'row.*.bill_image' => ['nullable'],
            'row.*.petrol_machine_image' => ['nullable'],
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
            return back()->with('errors', $validator->errors());
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


        return redirect()->route('fuel-expenses.index')->with('success', 'FuelExpense created successfully.');
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

        $fuelExpense = Invoice::where('company_id',auth()->user()->active_company())->find($id);

        return view('fuel-expense.show', compact('fuelExpense','breadcrumbs'));
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
        $edit = true;
        return view('fuel-expense.edit', compact('fuelExpense', 'edit', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  FuelExpense $fuelExpense
     * *
     */
    public function update(Request $request, $invoice_id)
    {
        // dd("knktgnk");
        // Validate the request data
         $company_id = auth()->user()->active_company();
        // dd($invoice_id);
        $invoice = Invoice::where('id', $invoice_id)->where('company_id', $company_id)
            ->first();
        if ($invoice->document_status != "draft") {
            return redirect()->back()->with('error', 'With This Status You can not Edit the document');
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
            'row.*.row_id' => ['nullable'],
            'row.*.line_amount' => ['required'],
            'row.*.meter_reading_km' => ['required'],
            'row.*.fuel_quantity_liters' => ['required'],
            'row.*.total_fuel_cost' => ['required'],
            'row.*.meter_reading_image' => ['nullable'],
            'row.*.bill_image' => ['nullable'],
            'row.*.petrol_machine_image' => ['nullable'],
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
            return back()->with('errors', $validator->errors());
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

        return redirect()->route('fuel-expenses.index')
            ->with('success', 'FuelExpense updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $company_id = auth()->user()->active_company();
        $invoice = Invoice::where('id', $id)->where('company_id', $company_id)
            ->first();
            if ($invoice->document_status != "draft") {
            return redirect()->back()->with('error', 'With This Status You can not delete the document');
        }
        $tolltax = Invoice::where('id', $id)->where('company_id', $company_id)->where('document_type_id', '3')
            ->where('document_status', 'draft')->delete();
        // $fuelExpense = FuelExpense::find($id)->delete();

        return redirect()->route('fuel-expenses.index')
            ->with('success', 'FuelExpense deleted successfully');
    }
      public function destroy_row($invoice_line_id)
    {
        $company_id = auth()->user()->active_company();
        $check = InvoiceLine::where('id', $invoice_line_id)
            ->whereHas('invoice', function ($invoice) use ($company_id) {
                return $invoice->where('company_id', $company_id)
                    ->where('document_type_id', '3')
                    ->where('document_status', 'draft');
            })
            // ->where('is_checked', null)
            // ->where('is_service_charge', 0)
            ->first();
        if ($check) {
            InvoiceLine::find($check->id)->delete();
            return response()->json(['success' => 'Deleted Successfully'], 200);
        } else {
            return response()->json(['success' => 'Sorry You cannot Delete this Row'], 401);
        }
    }
}
