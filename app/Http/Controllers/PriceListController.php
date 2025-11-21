<?php

namespace App\Http\Controllers;


use App\Models\Invoice;
use App\Models\InvoiceDocumentType;
use App\Models\MaterialInout;
use App\Models\MaterialInoutLine;
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
class PriceListController extends Controller
{
    // static $ignores = ['deleteActivityRow' => true,'fetchPoLines'=>true,'fetchPurchaseOrders'=>true];
    static $ignores = ['deleteActivityRow' => true,];
    static $role_module_id = 59;
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
                'name'=>"Price List",
                'link'=>route("price-lists.index"),
                'active'=>true,
            ]
        ];

        $perPage = $request->input('perPage', 10);
        $price_lists = PriceList::where('company_id',auth()->user()->active_company())->paginate($perPage);

        return view('price-list.index', compact('price_lists','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $price_lists->perPage());
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
                'name'=>"Price List",
                'link'=>route("price-lists.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("price-lists.create"),
                'active'=>true,
            ]
        ];
        $activity = new PriceList();
        return view('price-list.create', compact('activity','breadcrumbs'));
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
			
			'name' => ['required'],
			'description' => ['nullable'],
			'currency' => ['nullable'],
			'price_precision' => ['nullable'],
			'sales_price_list' => ['nullable'],
			'price_includes_tax' => ['nullable'],
			'enforce_price_limit' => ['nullable'],
			'is_default' => ['nullable'],
			'is_active' => ['nullable'],
			
			// 'rma' => ['nullable'],

            // version lines validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no'=>['nullable'],
            'rows.*.name'=>['required'],
            'rows.*.description'=>['nullable'],
            'rows.*.valid_from' => ['nullable'],
            'rows.*.is_active' => ['nullable'],
        ]);
        if (!isset($request['rows']) || count($request['rows']) < 1) {
            return back()->withErrors(['errors' => 'At least one Version line is required.'])->withInput();
        }
        // dd($validator);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        

        $payload['data'] =$data;
        // dd($data);
        PriceList::store_price_list($payload);

        return redirect()->route('price-lists.index')->with('success', 'PriceList created successfully.');
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
                'name'=>"Price List",
                'link'=>route("price-lists.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("price-lists.show",$id),
                'active'=>true,
            ]
        ];
        $activity = PriceList::where('company_id',auth()->user()->active_company())->find($id);

        return view('price-list.show', compact('activity','breadcrumbs'));
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
                'name'=>"Price List",
                'link'=>route("price-lists.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("price-lists.edit",$id),
                'active'=>true,
            ]
        ];
        $activity = PriceList::where('company_id',auth()->user()->active_company())->find($id);

        return view('price-list.edit', compact('activity','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $activity
     * *
     */
    public function update(Request $request,$price_list)
    {
        // Validate the request data
        $price_list = PriceList::find($price_list);
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
			'description' => ['nullable'],
			'currency' => ['nullable'],
			'price_precision' => ['nullable'],
			'sales_price_list' => ['nullable'],
			'price_includes_tax' => ['nullable'],
			'enforce_price_limit' => ['nullable'],
			'is_default' => ['nullable'],
			'is_active' => ['nullable'],
			
			// 'rma' => ['nullable'],

            // version lines validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no'=>['nullable'],
            'rows.*.name'=>['required'],
            'rows.*.description'=>['nullable'],
            'rows.*.valid_from' => ['nullable'],
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
        $payload['price_list']= $price_list;
        // dd($payload);
        PriceList::update_price_list($payload);

        return redirect()->route('price-lists.edit',$price_list->id)
            ->with('success', 'Price list updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        PriceList::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('price-lists.index')
            ->with('success', 'PriceList deleted successfully');
    }
    public function deleteActivityRow($id)
    {
        try {
            // Find the row by ID and delete it
            $version = PriceListVersion::findOrFail($id);
            if($version->priceList()->exists()){
                // dd('in the main row',$activity_line);
                return response()->json(['error' => true, 'message' => 'you cant remove this row it is associated with price list ']);
            }
            else{
                // dd('in the else row',$activity_line);
                $version->delete();
                return response()->json(['success' => true, 'message' => 'Row removed successfully']);
            }


        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove row'], 500);
        }
    }

    
}
