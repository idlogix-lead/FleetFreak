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
class TollTaxController extends Controller
{
    // static $ignores = ['deleteActivityRow' => true];
    static $role_module_id = 37;
    static $ignores = [ 'destroy_row' => true];

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
                'name' => "TollTax",
                'link' => route("toll-taxes.index"),
                'active' => true,
            ]
        ];
        // company_id
        $perPage = $request->input('perPage', 10);
        $tollTaxes = Invoice::where('document_type_id', '2')
            ->where('company_id', auth()->user()->active_company())
            ->orderByDesc('id')
            ->paginate($perPage);

        //    dd($tollTaxes);

        return view('toll-tax.index', compact('tollTaxes', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $tollTaxes->perPage());
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
                'name' => "TollTax",
                'link' => route("toll-taxes.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("toll-taxes.create"),
                'active' => true,
            ]
        ];
        $tolltax = new Invoice();
        $activity = Activity::getActiveActivity(auth()->user()->active_company());
        // dd($activity);
        // activityLines
        return view('toll-tax.create', compact('tolltax', 'activity', 'breadcrumbs'));
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
            'row.*.check_point' => ['required'],
            'row.*.picture_of_toll_tax' => ['nullable'],
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
        // dd($data['row'][0]['check_point']);
        $data['created_by'] = auth()->user()->id;
        // $row = $data['row'];
        // unset
        $company_id = auth()->user()->active_company();
        $data['document_type_id'] = 2; // for tolltax
        $document_id = InvoiceDocumentType::find($data['document_type_id']);
        // $document_no = Invoice::generate_document_no($company_id, 'maintenance');
        $document_no = Invoice::generate_document_no1($company_id, $document_id);
        // dd($data);
        $payload = [];
        $payload['data'] = $data;
        $payload['document_no'] = $document_no;
        Invoice::store_toll_tax($payload);


        return redirect()->back()->with('success', 'Toll Tax Invoice created successfully.');
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
                'name' => "TollTax",
                'link' => route("toll-taxes.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("toll-taxes.show", $id),
                'active' => true,
            ]
        ];
        $tolltax = Invoice::where('company_id',auth()->user()->active_company())->find($id);

        return view('toll-tax.show', compact('tolltax', 'breadcrumbs'));
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
                'name' => "TollTaxe",
                'link' => route("toll-taxes.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("toll-taxes.edit", $id),
                'active' => true,
            ]
        ];
        // dd($id);
        $tolltax = Invoice::where('document_type_id', '2')
            ->where('company_id', auth()->user()->active_company())
            ->with([
                'invoiceLines.invoiceLineProducts',
                'invoiceLines.activityLine'
            ])
            ->where('id', $id)->first();
        // $tolltax = Invoice::where('document_type_id', '2')
        // ->where('company_id', auth()->user()->active_company())
        // ->with([
        //     'invoiceLines'
        // ])
        // ->where('id', $id)->first();
        $edit = true;
        // dd($tolltax);
        // dd($tolltax[0]->check_point);
    //    dd();
        // dd($maintenance);
        return view('toll-tax.edit', compact('tolltax', 'edit', 'breadcrumbs'));
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
        $company_id = auth()->user()->active_company();
        // dd($invoice_id);
        $invoice = Invoice::where('id', $invoice_id)->where('company_id', $company_id)
            ->first();
        if ($invoice->document_status != "draft") {
            return redirect()->back()->with('error', 'With This Status You can not Edit the document');
        }
        $data = $request->all();
        $imageFields = ['picture_of_toll_tax' ];
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
            'row.*.check_point' => ['required'],
            'row.*.picture_of_toll_tax' => ['nullable'],

            // 'row' => ['required', 'array'],
            'row.*.row_id' => ['nullable'],
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
        Invoice::update_toll_tax($payload);

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
        $invoice = Invoice::where('id', $invoice_id)->where('company_id', $company_id)
            ->first();
        if ($data['document_status'] == 'completed') {
        }
        //----------
        return redirect()->back()->with('success', 'Toll Tax Invoice Updated successfully.');
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
        $tolltax = Invoice::where('id', $id)->where('company_id', $company_id)->where('document_type_id', '2')
            ->where('document_status', 'draft')->delete();

        return redirect()->route('toll-taxes.index')
            ->with('success', 'Invoice deleted successfully');
    }

    public function destroy_row($invoice_line_id)
    {
        // dd('ff');
        $company_id = auth()->user()->active_company();
        $check = InvoiceLine::where('id', $invoice_line_id)
            ->whereHas('invoice', function ($invoice) use ($company_id) {
                return $invoice->where('company_id', $company_id)
                    ->where('document_type_id', '2')
                    ->where('document_status', 'draft');
            })
            // ->where('is_checked', null)
            // ->where('is_service_charge', 0)
            ->first();
        if ($check) {
            // dd($check);
            InvoiceLine::find($check->id)->delete();
            return response()->json(['success' => 'Deleted Successfully'], 200);
        } else {
            return response()->json(['success' => 'Sorry You cannot Delete this Row'], 401);
        }
    }
}
