<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class PartnerController
 * @package App\Http\Controllers
 */
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $role_module_id = 6;
    public $my_companies;

    // ignored permission functions
    static $ignores = ['partner_dropdown'=>true,];

    function __construct(){
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });


    }
    // public function partner_dropdown($type,$business_partner_id){
    //     // dd($type,$partner_id);
    //     $search = $_GET['q']??'';
    //     if($search){
    //         // dd($search);
    //         $partners = Partner::where('partner_type',$type)
    //         ->where('business_partner_id',$business_partner_id)
    //         ->where(function($query) use($search){
    //             $query->where('name','LIKE','%'.$search.'%')
    //             ->orWhere('cnic','LIKE','%'.$search.'%')
    //             ->orWhere('passport','LIKE','%'.$search.'%');
    //         })
    //         ->get();
    //         return response()->json($partners,200);
    //     }
    // }
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"Employee",
                'link'=>route("employees.index"),
                'active'=>true,
            ]
        ];
        $query = $request->input('query');
        $perPage = $request->input('perPage', 10);

        $partners = Partner::checkGlobal(6)->where('actor_id',7)->where('company_id',auth()->user()->active_company())->whereIn('employee_type', ['management', 'office_staff'])->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('name','ILIKE', '%' . $query . '%');
        })->paginate($perPage);

        // $permissions = auth()->user()->get_user_role_session_permissions();
        // $target_method = ''
        // $permission = $permissions->filter(function($value, $key) use($target_method){
        //     $module =  $value->role_module_functions->filter(function($value, $key)  use($target_method){
        //         return $value->method == $target_method;
        //     });
        //     return $module->first();
        // })->first();
        // dd($permissions->where('role_permission_type.is_read',1));

        return view('employee.index', compact('partners','breadcrumbs'))
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
                'name'=>"Employee",
                'link'=>route("employees.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("employees.create"),
                'active'=>true,
            ]
        ];
        // $partner = new Partner();
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'all';
        return view('employee.create', compact('breadcrumbs', 'form_type'));
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
        $call_from_employee_controller = true;
            $partner_validator = Validator::make($request->all(), [
                'name' => ['required','string'],
                // 'partner_type' => ['required'],
                'email' => ['required','email',Rule::unique('users'),Rule::unique('partners')],
                'phone_no' => ['string',],
                'whatsapp_no' => ['nullable',],
                'cnic' => ['required','string'],
                'address1' => ['string','nullable'],
                'address2' => ['string','nullable'],
                'address3' => ['string','nullable'],
                'city' => ['nullable'],
                'country' => ['nullable'],
                'create_user'=> ['required'],
                'passport'=>['nullable'],
                // 'business_partner_id'=> ['nullable'],
                // 'company_name'=> ['nullable'],
                'employee_type'=>['required'],

            ]);


        // Validate the request data
        if ($partner_validator->fails()) {
            //dd($partner_validator->errors());
            return back()->with('errors', $partner_validator->errors());
        }

        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['created_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 7; // 7 is employee actor id

        $payload['partner_data'] = $partner_data;
        $payload['call_from_employee_controller'] = $call_from_employee_controller;
        // $partner = Partner::create($data);

        if ($request['create_user'] == 1) {
            // Validate the user data
            $user_validator = Validator::make($request->all(), [
                'name' => ['required','string'],
                'email' => ['required','email',Rule::unique('users'),Rule::unique('partners'),
                ],
                'password' => ['required','min:8','confirmed'],
                'password_confirmation' => ['required','min:8'],

            ]);

            // Check if validation fails
            if ($user_validator->fails()) {
                // Return validation errors
                // dd($user_validator->errors());
                return back()->with('errors', $user_validator->errors());
            }
            $user_data = $user_validator->validated();
            if($partner_data['employee_type']=='management'){
                $user_data['role_id']= 6; // management role id
            }
            else{
                $user_data['role_id']= 7; // office_staff role id

            }

            $payload['created_by'] = auth()->user()->id;
            // $payload['image'] = 'profile_images/default/default.jpeg';
            $payload['user_data'] = $user_data;

        }
        //dd($payload);
        // if($partner_data['partner_type'] =='customer'){
        //     Partner::store_customer($payload);
        // }
        // if($partner_data['partner_type'] =='business'){
        //     Partner::store_business($payload);
        // }

        // dd($payload);
        Partner::store_employee($payload);





        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
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
                'name'=>"Employee",
                'link'=>route("employees.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("employees.show",$id),
                'active'=>true,
            ]
        ];
        $partner = Partner::checkGlobal(6)->where('company_id',auth()->user()->active_company())->find($id);

        return view('employee.show', compact('partner','breadcrumbs'));
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
                'name'=>"Employee",
                'link'=>route("employees.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("employees.edit",$id),
                'active'=>true,
            ]
        ];
        $partner = Partner::checkGlobal(6)->where('company_id',auth()->user()->active_company())->find($id);
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'notall';


        return view('employee.edit', compact('partner','breadcrumbs', 'form_type'));
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
        $partner = Partner::find($partner);
        $call_from_employee_controller=true;

        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            'name' => ['required','string'],
            // 'partner_type' => ['nullable'],
            'email' => ['required'],
            'phone_no' => ['string'],
            'whatsapp_no' => ['nullable',],
            'cnic' => ['required','string'],
            'address1' => ['string','nullable'],
            'address2' => ['string','nullable'],
            'address3' => ['string','nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            'create_user'=> ['nullable'],
            'passport'=>['nullable'],
            // 'business_partner_id'=> ['nullable'],
            // 'company_name'=> ['nullable'],
            'employee_type'=>['nullable'],


            ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return back()->with('errors', $partner_validator->errors());
        }
        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['updated_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 7; // 7 is employee actor id
        $payload['partner_data'] = $partner_data;
        $payload['call_from_employee_controller'] = $call_from_employee_controller;


        // if ($partner_data['create_user'] == 1) {
        //     // Validate the user data
        //     $user_validator = Validator::make($request->all(), [
        //         'name' => ['required','string'],
        //         'email' => ['required','email',Rule::unique('users'),Rule::unique('partners'),
        //         ],
        //         'password' => ['required','min:8','confirmed'],
        //         'password_confirmation' => ['required','min:8'],

        //     ]);

        //     // Check if validation fails
        //     if ($user_validator->fails()) {
        //         // Return validation errors
        //         return back()->withErrors($user_validator)->withInput();
        //     }
        //     $user_data = $user_validator->validated();
        //     $payload['user_data'] = $user_data;

        // }
        $payload['partner'] = $partner;
        // if($partner_data['partner_type']=='customer'){
        //     Partner::update_customer($payload);
        // }
        // if($partner_data['partner_type']=='business'){
        //     Partner::update_business($payload);
        // }

            Partner::update_employee($payload);


        //dd($payload);
        // Partner::update_partner($payload);

        //$partner->update($parter_data);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $partner = Partner::where('company_id', auth()->user()->active_company())->findOrFail($id)->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully');
    }

}
