<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Models\Event;


/**
 * Class PartnerController
 * @package App\Http\Controllers
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $role_module_id = 12;
    public $my_companies;
    // ignored permission functions
    static $ignores = ['partner_dropdown'=>true,'search'=>true, 'create_customer_from_order'=>true];

    function __construct(){
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });

    }
    public function partner_dropdown($business_partner_id){
        //dd($type,$business_partner_id);
        $search = $_GET['q']??'';
        if($search){
        //    dd($search);
            $partners = Partner::where('actor_id',6)->where('company_id',auth()->user()->active_company())
            // ->where('business_partner_id',$business_partner_id)
            ->where(function($query) use($search){
                $query->where('name','ILIKE','%'.$search.'%')
                ->orWhere('cnic','ILIKE','%'.$search.'%')
                ->orWhere('passport','ILIKE','%'.$search.'%');
            })
            ->get();
            return response()->json($partners,200);
        }
        else{
            // dd('here');
            $partners = Partner::where('actor_id',6)->where('company_id',auth()->user()->active_company())
            // ->where('business_partner_id',$business_partner_id)
          
            ->get();
            return response()->json($partners,200);
        }
    }
    public function search()
    {
        // $company =  auth()->user()->companies->first();
        $company_id=auth()->user()->active_company();
        $search = $search = $_GET['term']??'';
        if($search){
            $customers = Partner::where('company_id',$company_id)->where('actor_id',6)->where('name', 'ILIKE', '%' . $search . '%')->get();
            return response()->json($customers);
        }

    }

    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"Customer",
                'link'=>route("customers.index"),
                'active'=>true,
            ]
        ];
        $query = $request->input('query');
        $perPage = $request->input('perPage', 10);
        $company_id = auth()->user()->active_company();

        // 4 is agent actor id
        //6 is business customer actor id
        //8 is walkin_customer actor id
        if(auth()->user()->actor_id== 4){
            $partners = Partner::checkGlobal(12)->whereIn('actor_id',[6])->where('business_partner_id',auth()->user()->partner_id)->where('company_id',$company_id)->orderBy('created_at', 'desc')->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('name','ILIKE', '%' . $query . '%');
            })->paginate($perPage);
        }
        else{
            $partners = Partner::checkGlobal(12)->whereIn('actor_id',[6,8])->where('company_id',$company_id)->orderBy('created_at', 'desc')->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('name','ILIKE', '%' . $query . '%');
            })->paginate($perPage);
        }
        // $partners = Partner::checkGlobal(12)->where('actor_id','6')->orWhere('actor_id','8')->orderBy('created_at', 'desc')->paginate();




        // $permissions = auth()->user()->get_user_role_session_permissions();
        // $target_method = ''
        // $permission = $permissions->filter(function($value, $key) use($target_method){
        //     $module =  $value->role_module_functions->filter(function($value, $key)  use($target_method){
        //         return $value->method == $target_method;
        //     });
        //     return $module->first();
        // })->first();
        // dd($permissions->where('role_permission_type.is_read',1));

        // dd($partners); 

        return view('customer.index', compact('partners','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $partners->perPage());
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
                'name'=>"Customer",
                'link'=>route("customers.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("customers.create"),
                'active'=>true,
            ]
        ];
        // $partner = new Partner();
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'all';
        return view('customer.create', compact('breadcrumbs', 'form_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function store(Request $request)
    {
        $payload = [];

            $partner_validator = Validator::make($request->all(), [
                'name' => ['required','string'],
                // 'partner_type' => ['required'],
                // 'email' => ['required','email', Rule::unique('users', 'email'),Rule::unique('partners', 'email')],

                'email' => ['nullable'],
                'phone_no' => ['nullable'],
                'whatsapp_no' => ['required'],
                'prefix_whatsapp'=>['required'],
                'prefix_phone'=>['nullable'],

                'cnic' => ['nullable','string'],
                'address1' => ['string','nullable'],
                'address2' => ['string','nullable'],
                'address3' => ['string','nullable'],
                'city' => ['nullable'],
                'country' => ['nullable'],
                'create_user'=> ['nullable'],
                'passport'=>['nullable'],
                'business_partner_id'=> ['required'],
                // 'company_name'=> ['nullable'],
                // 'employee_type'=>['nullable'],

            ]);

        // Validate the request data
        if ($partner_validator->fails()) {
            //dd($partner_validator->errors());
            return back()->with('errors', $partner_validator->errors())->withInput();

            // return back()->with('errors', $partner_validator->errors());
        }
        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['created_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 6;
        $partner_data['company_id'] = auth()->user()->active_company();
        $payload['partner_data'] = $partner_data;



        //dd($payload);

        Partner::store_customer($payload);

        // if($partner_data['partner_type'] =='business'){
        //     Partner::store_business($payload);
        // }
        // if($partner_data['partner_type'] =='employee'){
        //     // dd($payload);
        //     Partner::store_employee($payload);
        // }




        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
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
                'name'=>"Customer",
                'link'=>route("customers.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("customers.show",$id),
                'active'=>true,
            ]
        ];
        $partner = Partner::checkGlobal(12)->where('company_id',auth()->user()->active_company())->find($id);

        return view('customer.show', compact('partner','breadcrumbs'));
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
                'name'=>"Customer",
                'link'=>route("customers.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("customers.edit",$id),
                'active'=>true,
            ]
        ];
        $partner = Partner::checkGlobal(12)->where('company_id',auth()->user()->active_company())->find($id);
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'all';


        return view('customer.edit', compact('partner','breadcrumbs', 'form_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Partner $partner
     * *
     */
    public function update(Request $request,$partner,)
    {
        $payload = [];
        $partner  =Partner::find($partner);

        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            'name' => ['required','string'],
            // 'partner_type' => ['nullable'],
            'email' => ['nullable',],
            'phone_no' => ['string'],
            'whatsapp_no' => ['nullable',],
            'prefix_whatsapp'=>['required'],
            'prefix_phone'=>['nullable'],

            'cnic' => ['nullable','string'],
            'address1' => ['string','nullable'],
            'address2' => ['string','nullable'],
            'address3' => ['string','nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            'create_user'=> ['nullable'],
            'passport'=>['nullable'],
            'business_partner_id'=> ['nullable'],
            // 'company_name'=> ['nullable'],
            // 'employee_type'=>['nullable'],


            ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return back()->with('errors', $partner_validator->errors());
        }
        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['updated_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 6;
        $partner_data['company_id'] = auth()->user()->active_company();
        $payload['partner'] = $partner;
        $payload['partner_data'] = $partner_data;


        // dd($payload);
        Partner::update_customer($payload);

        // if($partner_data['partner_type']=='business'){
        //     Partner::update_business($payload);
        // }
        // if($partner_data['partner_type']=='employee'){
        //     Partner::update_employee($payload);
        // }
        // if($partner_data['partner_type']=='agent'){
        //     Partner::update_agent($payload);
        // }
        //dd($payload);
        // Partner::update_partner($payload);

        //$partner->update($parter_data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $partner = Partner::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }
    public function create_customer_from_order(Request $request)
    {
        $data = json_decode($request->input('data'), true);
        // dd($data);
        $payload = [];
        $customer_validator = Validator::make($data, [
            //
            'name' => ['required','string'],
            // 'partner_type' => ['nullable'],
            'email' => ['nullable',],
            'phone_no' => ['string'],
            'whatsapp_no' => ['nullable',],
            'prefix_whatsapp'=>['required'],
            'prefix_phone'=>['nullable'],

            'cnic' => ['nullable','string'],
            'address1' => ['string','nullable'],
            'address2' => ['string','nullable'],
            'address3' => ['string','nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            'create_user'=> ['nullable'],
            'passport'=>['nullable'],
            'business_partner_id'=> ['nullable'],


        ]);
        if ($customer_validator->fails()) {
            // dd($customer_validator->errors());
            return response()->json(['errors', $customer_validator->errors()],402);
        }
        $partner_data = $customer_validator->validated();
        $partner_data['created_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 6;
        $partner_data['company_id'] = auth()->user()->active_company();
        $payload['partner_data'] = $partner_data;

        // dd($payload);

        $partners = Partner::store_customer($payload);
        //return redirect()->back()->with('success', 'Partner deleted successfully');
        // dd($partners);
            if ($partners) {
                return response()->json($partners,200
                    // 'id' => $partners->id,
                    // 'name' => $partners->name,
                );
            } else {
                return response()->json([
                    'error' => 'Failed to create partner.',
                ], 500);
            };






        // Validate the incoming request data


        // Create a new customer using the validated data
        // $customer = Customer::create([
        //     // Assign the validated data to the appropriate columns of the customers table
        // ]);

        // // Check if the customer was created successfully
        // if ($customer) {
        //     // If successful, return a success response
        //     return response()->json(['success' => true, 'message' => 'Customer created successfully'], 200);
        // } else {
        //     // If not successful, return an error response
        //     return response()->json(['success' => false, 'error' => 'Failed to create customer'], 500);
        // }
    }
    public function export(){
        return Excel::download(new CustomerExport, 'customers.xlsx');
    }

}
