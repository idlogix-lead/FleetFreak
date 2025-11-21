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
use Illuminate\Support\Benchmark;

/**
 * Class Maintenance
 * @package App\Http\Controllers
 */
class InspectionController extends Controller
{
    static $ignores = ['create_maintainence' => true];
    static $role_module_id = 44;
    public $my_companies;
    public $is_inspection;

    function __construct($is_inspection = true){
        $this->is_inspection = $is_inspection;
        if(!$is_inspection){
            $this->middleware('RolePermissions');
        }
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
        $is_inspection = $this->is_inspection;
        if(!$this->is_inspection){
            $breadcrumbs = [
                [
                    'name'=>"Maintenance",
                    'link'=>route("maintenances.index"),
                    'active'=>true,
                ]
            ];
        }else{
            $breadcrumbs = [
                [
                    'name'=>"Inspections",
                    'link'=>route("inspections.index"),
                    'active'=>true,
                ],
            ];
        }

        $perPage = $request->input('perPage', 10);
        // company_id
        $maintenances = Invoice::where('document_type_id', ($is_inspection?5:1))->whereIn('document_status',($is_inspection?['completed','draft']:['pending']))
        ->where('company_id', auth()->user()->active_company())
        ->orderByDesc('id')
        ->paginate($perPage);
        // dump($maintenances);

        return view('inspection.index', compact('maintenances','breadcrumbs','is_inspection'))
        ->with('i', (request()->input('page', 1) - 1) * $maintenances->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    public function create()
    {
        $is_inspection = $this->is_inspection;
        if($this->is_inspection){
            $breadcrumbs = [
                [
                    'name'=>"Inspections",
                    'link'=>route("inspections.index"),
                    'active'=>false,
                ],
                [
                    'name'=>"Create",
                    'link'=>route("inspections.create"),
                    'active'=>true,
                ]
            ];
        }else{
            $breadcrumbs = [
                [
                    'name'=>"Maintenance",
                    'link'=>route("maintenances.index"),
                    'active'=>false,
                ],
                [
                    'name'=>"Create",
                    'link'=>route("maintenances.create"),
                    'active'=>true,
                ]
            ];
        }
        $maintenance = new Invoice();
        $activity = Activity::getActiveActivity(auth()->user()->active_company());
        // dd($activity);
        // activityLines
        return view('inspection.create', compact('maintenance', 'activity','breadcrumbs','is_inspection'));
    }
    public function create_maintainence($id)
    {
        
        $is_inspection = $this->is_inspection;   
        $inspection_id = Invoice::find($id);

            $breadcrumbs = [
                [
                    'name'=>"Maintenance",
                    'link'=>route("maintenances.index"),
                    'active'=>false,
                ],
                [
                    'name'=>"Create",
                    'link'=>route("maintenances.create"),
                    'active'=>true,
                ]
            ];
        $maintenance = new Invoice();
        $activity = Activity::getActiveActivity(auth()->user()->active_company()); 
        // dd($activity);
        // activityLines
        return view('inspection.create', compact('maintenance', 'activity','breadcrumbs','is_inspection','inspection_id'));
    }
    static function document_generator_template($company_id){
        $maintenance = new Invoice();
        $activity = Activity::getActiveActivity($company_id);
        return [
            $maintenance->getColumnNames(),
            $activity
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function store(Request $request)
    {
        $is_inspection = $this->is_inspection;
        // dd($request);
        // Validate the request data
        $dynamic_rules = [
            'row.*.quantity' => $is_inspection ? ['nullable'] : ['required'],
            'row.*.rate' => $is_inspection ? ['nullable'] : ['required'],
            'row.*.line_total' => $is_inspection ? ['nullable'] : ['required'],
        ];
        $base_rules = [
            // 'document_no' => ['required'],
			// 'company_id' => ['required'],
			'vehicle_id' => ['required'],
			'business_partner_id' => ['required'],
			'date' => ['required'],
			'start_time' => ['required'],
			'end_time' => ['required'],
			'description' => ['nullable','string'],
			'total_amount' => ['required'],
			'grand_total_amount' => ['required'],
			'document_status' => ['required'],
            'inspection_id'=>['nullable'],
			// 'document_type' => ['required'],
			// 'document_type_id' => ['required'],

            'row' => ['required', 'array'],
            'row.*.is_checked' => ['nullable'],
            'row.*.activity_id' => ['nullable'],
            'row.*.is_service_charge' => ['required'],
            'row.*.description' => ['nullable'],
            // 'row.*.quantity' => ['required'],
            // 'row.*.rate' => ['required'],
            // 'row.*.line_total' => ['required'],
            'row.*.product_id' => ['nullable'],
            // 'row.*.product_id.*' => ['required'],
        ];
        // old code-----------------------
        // $validator = Validator::make($request->all(), [
		// 	// 'document_no' => ['required'],
		// 	// 'company_id' => ['required'],
		// 	'vehicle_id' => ['required'],
		// 	'business_partner_id' => ['required'],
		// 	'date' => ['required'],
		// 	'description' => ['nullable','string'],
		// 	'total_amount' => ['required'],
		// 	'grand_total_amount' => ['required'],
		// 	'document_status' => ['required'],
		// 	// 'document_type' => ['required'],
		// 	// 'document_type_id' => ['required'],

        //     'row' => ['required', 'array'],
        //     'row.*.is_checked' => ['nullable'],
        //     'row.*.activity_id' => ['nullable'],
        //     'row.*.is_service_charge' => ['required'],
        //     'row.*.description' => ['nullable'],
        //     'row.*.quantity' => ['required'],
        //     'row.*.rate' => ['required'],
        //     'row.*.line_total' => ['required'],
        //     'row.*.product_id' => ['nullable'],
        //     // 'row.*.product_id.*' => ['required'],
        // ]);
        //-------------------------------end here
        $validator = Validator::make($request->all(), array_merge($base_rules, $dynamic_rules));
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
        $data['document_type_id'] = ($is_inspection?5:1); // for maintenance
        $data['company_id'] = $company_id;
        $document_id = InvoiceDocumentType::find($data['document_type_id']);
        // $document_no = Invoice::generate_document_no($company_id, 'maintenance');
        $document_no = Invoice::generate_document_no1($company_id, $document_id);
        // dd($data);
        $payload = [];
        $payload['data'] = $data;
        $payload['document_no'] = $document_no;
        $payload['is_inspection'] = $is_inspection;
        // dd($payload);
        Invoice::store_maintainence($payload);

        if($request->has('inspection_id')){

            return redirect()->route('maintenances.create')->with('success', 'Maintenance created successfully');
        }
        else{
        return redirect()->back()->with('success', ($is_inspection?"Inspection":"Maintenance").' Invoice created successfully.');
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    public function show($id)
    {
        $is_inspection = $this->is_inspection;
        if($this->is_inspection){
            $breadcrumbs = [
                [
                    'name'=>"Inspections",
                    'link'=>route("inspections.index"),
                    'active'=>false,
                ],
                [
                    'name'=>"Show",
                    'link'=>route("inspections.show",$id),
                    'active'=>true,
                ]
            ];
        }else{
            $breadcrumbs = [
                [
                    'name'=>"Maintenance",
                    'link'=>route("maintenances.index"),
                    'active'=>false,
                ],
                [
                    'name'=>"Show",
                    'link'=>route("maintenances.show",$id),
                    'active'=>true,
                ]
            ];
        }
        // $breadcrumbs = [
        //     [
        //         'name'=>"Maintenance",
        //         'link'=>route("maintenances.index"),
        //         'active'=>false,
        //     ],
        //     [
        //         'name'=>"Show",
        //         'link'=>route("maintenances.show",$id),
        //         'active'=>true,
        //     ]
        // ];
        $maintenance = Invoice::find($id);

        return view('inspection.show', compact('maintenance','breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * *
     */
    public function edit($id)
    {
        $is_inspection = $this->is_inspection;

        if($this->is_inspection){
            $breadcrumbs = [
                [
                    'name'=>"Inspections",
                    'link'=>route("inspections.index"),
                    'active'=>false,
                ],
                [
                    'name'=>"Edit",
                    'link'=>route("maintenances.edit",$id),
                    'active'=>true,
                ]
            ];
        }else{
            $breadcrumbs = [
                [
                    'name'=>"Maintenance",
                    'link'=>route("maintenances.index"),
                    'active'=>false,
                ],
                [
                    'name'=>"Edit",
                    'link'=>route("maintenances.edit",$id),
                    'active'=>true,
                ]
            ];
        }

        $maintenance = Invoice::where('document_type_id', ($is_inspection?5:1))
        ->where('company_id', auth()->user()->active_company())
        ->with([
            'invoiceLines.invoiceLineProducts',
            'invoiceLines.activityLine'
        ])
        ->where('id',$id)->first();
        $edit = true;
        // dd($maintenance);
        return view('inspection.edit', compact('maintenance','edit','breadcrumbs','is_inspection'));
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
        $is_inspection = $this->is_inspection;
        $company_id = auth()->user()->active_company();
        // dd($invoice_id);
        $invoice = Invoice::where('id', $invoice_id)->where('company_id', $company_id)
        ->first();
        if($invoice->document_status != "draft"){
            return redirect()->back()->with('error', 'With This Status You can not Edit the document');
        }
        // Validate the request data
        $validator = Validator::make($request->all(), [
			// 'document_no' => ['required'],
			// 'company_id' => ['required'],
			'vehicle_id' => ['required'],
			'business_partner_id' => ['required'],
			'date' => ['required'],
			'description' => ['nullable','string'],
			'total_amount' => ['required'],
			'grand_total_amount' => ['required'],
			'document_status' => ['required'],
			// 'document_type' => ['required'],

            'row' => ['required', 'array'],
            'row.*.row_id' => ['nullable'],
            'row.*.is_checked' => ['nullable'],
            'row.*.activity_id' => ['nullable'],
            'row.*.is_service_charge' => ['required'],
            'row.*.description' => ['nullable'],
            'row.*.quantity' => ['required'],
            'row.*.rate' => ['required'],
            'row.*.line_total' => ['required'],
            'row.*.product_id' => ['nullable'],
            // 'row.*.product_id.*' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $data['company_id'] = $company_id;
        $data['document_type_id'] = ($is_inspection?5:1); // for maintenance
        $payload = [];

        $company_id = auth()->user()->active_company();

        $payload['data'] = $data;
        $payload['invoice'] = $invoice;
        $payload['is_inspection'] = $is_inspection;

        // Invoice::createNextMaintenanceEvent($data, 1);
        // dd('out');
        Invoice::update_maintainence($payload);

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
        if($data['document_status'] == 'completed'){

        }
        //----------
        return redirect()->back()->with('success', ($is_inspection?"Inspection":"Maintenance").' Invoice Updated successfully.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $is_inspection = $this->is_inspection;
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
