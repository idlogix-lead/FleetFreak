<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Role;

/**
 * Class PartnerController
 * @package App\Http\Controllers
 */
class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $role_module_id = 11;
    // ignored permission functions
    // static $ignores = ['partner_dropdown'=>true, 'create_customer_from_order'=>true];

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }

    static $ignores = [
        'api_store' => true,
        'api_index' => true,
        'api_update'=>true,
        'api_show'=>true,
        'api_edit'=>true,
        'api_destroy'=>true,

    ];
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
    public function api_index()
    {
        $breadcrumbs = [
            [
                'name' => "Driver",
                'link' => route("drivers.index"),
                'active' => true,
            ],
        ];
        $company_id = auth()->user()->active_company();
        $partners = Partner::where('company_id',$company_id)->where('actor_id', [5])->get();

        // $permissions = auth()->user()->get_user_role_session_permissions();
        // $target_method = ''
        // $permission = $permissions->filter(function($value, $key) use($target_method){
        //     $module =  $value->role_module_functions->filter(function($value, $key)  use($target_method){
        //         return $value->method == $target_method;
        //     });
        //     return $module->first();
        // })->first();
        // dd($permissions->where('role_permission_type.is_read',1));

        return response()->json([
            // 'breadcrumbs' => $breadcrumbs,
            'drivers' => $partners]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    public function api_create()
    {
        $breadcrumbs = [
            [
                'name' => "Driver",
                'link' => route("drivers.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("drivers.create"),
                'active' => true,
            ],
        ];
        // $partner = new Partner();
        // $business_partner = Partner::where('partner_type','business')->get();
        // $form_type = 'all';
        return response()->json(['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function api_store(Request $request)
    {
        $payload = [];

        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            // 'name' => ['required','string'],
            // // 'partner_type' => ['required'],
            // 'email' => ['required','email',Rule::unique('users'),Rule::unique('partners')],
            // 'phone_no' => ['string'],
            // 'whatsapp_no' => ['nullable'],
            // 'cnic' => ['required','string'],
            // 'address1' => ['string','nullable'],
            // 'address2' => ['string','nullable'],
            // 'address3' => ['string','nullable'],
            // 'city' => ['nullable'],
            // 'country' => ['nullable'],
            // 'create_user'=> ['nullable'],
            // 'passport'=>['nullable'],
            // 'business_partner_id'=> ['nullable'],
            // 'company_name'=> ['nullable'],
            // // 'employee_type'=>['required'],
            // 'age'=>['required'],
            // 'experience'=>['required'],
            // 'akama'=>['required'],
            'name' => ['required','string'],
            // 'partner_type' => ['required'],
            'email' => ['required','email',Rule::unique('users'),Rule::unique('partners')],
            'phone_no' => ['required'],
            'whatsapp_no' => ['required'],
            'prefix_whatsapp'=>['required'],
            'prefix_phone'=>['nullable'],
            'prefix_emergency_contact1'=>['required'],
            'prefix_emergency_contact2'=>['required'],
            // 'nic_no'=>['required'],
            'license_country'=>['required'],
            'licensee_expiry_date'=>['required'],
            'emergency_contact_no1'=>['required'],
            'emergency_contact_no2'=>['required'],
            'nic_expiry_date'=>['required'],
            'emergency_contact_name'=>['required'],

            'cnic' => ['required','string'],
            'address1' => ['string','nullable'],
            'address2' => ['string','nullable'],
            'address3' => ['string','nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            'create_user'=> ['nullable'],
            'passport'=>['nullable'],
            'business_partner_id'=> ['nullable'],
            'company_name'=> ['nullable'],
            // 'employee_type'=>['required'],
            'age'=>['nullable'],
            'experience'=>['nullable'],
            'akama'=>['nullable'],
            'company_id'=>['nullable'],
            'driver_license'=>['required'],


        ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return response()->json(['errors' => $partner_validator->errors()], 400);
        }

        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['created_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 5;

        $payload['partner_data'] = $partner_data;
        // $partner = Partner::create($data);

        if ($request['create_user'] == 1) {
            // Validate the user data
            $user_validator = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'email' => ['required', 'email', Rule::unique('users'), Rule::unique('partners'),
                ],
                // 'image'=>['nullable','image','max:2048'],

                'password' => ['required', 'min:8', 'confirmed'],
                'password_confirmation' => ['required', 'min:8'],

            ]);

            // Check if validation fails
            if ($user_validator->fails()) {
                // Return validation errors
                // dd($user_validator->errors());
                return response()->json(['errors' => $user_validator->errors()], 400);
            }
            $user_data = $user_validator->validated();
            // $user_data['role_id']= 5;
            $user_data['role_id'] = Role::where('client_id', auth()->user()->client_id)->where('actor_id', 5)->value('id');
            $payload['created_by'] = auth()->user()->id;
            $payload['image'] = null;
            $payload['user_data'] = $user_data;

        }
        //dd($payload);
        // if($partner_data['partner_type'] =='customer'){
        //     Partner::store_customer($payload);
        // }
        // if($partner_data['partner_type'] =='business'){
        //     Partner::store_business($payload);
        // }
        // if ($partner_data['partner_type'] == 'employee') {
            Partner::store_employee($payload);
        // }
        // if($partner_data['partner_type'] =='agent'){
        //     Partner::store_agent($payload);
        // }

        return response()->json([
            'success' => 'Partner created successfully.',
            // 'partner' => $partner_data,
            // 'user' => $user_data
            'data'=>$payload
            ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    public function api_show($id)
    {
        $breadcrumbs = [
            [
                'name' => "Drivers",
                'link' => route("drivers.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("drivers.show", $id),
                'active' => true,
            ],
        ];
        $partner = Partner::find($id);

        return response()->json(['partner'=>$partner]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * *
     */
    public function api_edit($id)
    {
        $breadcrumbs = [
            [
                'name' => "Drivers",
                'link' => route("drivers.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("drivers.edit", $id),
                'active' => true,
            ],
        ];
        // $partner = Partner::find($id);
        $partner = Partner::checkGlobal(self::$role_module_id)->where('company_id', auth()->user()->active_company())->find($id);

        // $business_partner = Partner::where('partner_type','business')->get();
        // $form_type = 'notall';

        return response()->json(['partner'=>$partner]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Partner $partner
     * *
     */
    public function api_update(Request $request, $partner, )
    {
        $payload = [];
        $partner = Partner::find($partner);

        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            'name' => ['required','string'],
            // 'partner_type' => ['nullable'],
            'email' => ['required'],
            'phone_no' => ['string'],
            'whatsapp_no' => ['nullable',],
            'prefix_whatsapp'=>['required'],
            'prefix_phone'=>['nullable'],

            'prefix_emergency_contact1'=>['required'],
            'prefix_emergency_contact2'=>['required'],
            // 'nic_no'=>['required'],
            'license_country'=>['required'],
            'licensee_expiry_date'=>['required'],
            'emergency_contact_no1'=>['required'],
            'emergency_contact_no2'=>['required'],
            'nic_expiry_date'=>['required'],
            'emergency_contact_name'=>['required'],

            'cnic' => ['required','string'],
            'address1' => ['string','nullable'],
            'address2' => ['string','nullable'],
            'address3' => ['string','nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            'create_user'=> ['nullable'],
            'passport'=>['nullable'],
            'business_partner_id'=> ['nullable'],
            'company_name'=> ['nullable'],
            // 'employee_type'=>['required'],
            'age'=>['nullable'],
            'experience'=>['nullable'],
            'akama'=>['nullable'],
            'driver_license'=>['required'],


            ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return response()->json(['errors'=>$partner_validator->errors()],400);
        }
        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['updated_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 5;
        $payload['partner_data'] = $partner_data;

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
        // if ($partner_data['partner_type'] == 'employee') {
            Partner::update_employee($payload);
        // }
        // if($partner_data['partner_type']=='agent'){
        //     Partner::update_agent($payload);
        // }
        //dd($payload);
        // Partner::update_partner($payload);

        //$partner->update($parter_data);

        return response()->json(['success', 'Driver updated successfully','data'=>$payload],200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        // dd('fff');
        $partner = Partner::where('id', $id)->where('actor_id', 5)->first();

        if ($partner) {
            $partner->delete();
            return response()->json(['message' => 'Partner deleted successfully.']);
        } else {
            return response()->json(['message' => 'Partner not found.'], 404);
        }
    }

}
