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

/**
 * Class PartnerController
 * @package App\Http\Controllers
 */
class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $role_module_id = 6;
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }
    static $ignores = [
        'api_store' => true,
        'api_index' => true,
        'api_update' => true,
        'api_show' => true,
        'api_edit' => true,
        'api_total_driver_customer_vendor'=>true
    ];
    public function api_index()
    {
        $breadcrumbs = [
            [
                'name' => "Partner",
                'link' => route("partners.index"),
                'active' => true,
            ],
        ];
        $partners = Partner::paginate();

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
            'breadcrumbs' => $breadcrumbs,
            'partners' => $partners,
        ]);
    }

    public function api_total_driver_customer_vendor(){
      $user = auth()->user();

      $company = auth()->user()->active_company() ?? null;
     $total_drivers= Partner::where('company_id',$company)->where('actor_id',5)->count();
     $total_agents= Partner::where('company_id',$company)->where('actor_id',4)->count();
     $total_customers = Partner::where('company_id', $company)->where('actor_id', 6)->count();


    return response()->json([
    'total_drivers' => $total_drivers,
    'total_agents' => $total_agents,
    'total_customers'=>$total_customers

      ], 200);


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
                'name' => "Partner",
                'link' => route("partners.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("partners.create"),
                'active' => true,
            ],
        ];
        $partner = new Partner();
        return response()->json([
            'breadcrumbs' => $breadcrumbs,
            'partner' => $partner,
        ]);
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
            'name' => ['required', 'string'],
            'partner_type' => ['required'],
            'email' => ['required', 'email', Rule::unique('users'), Rule::unique('partners')],
            'phone_no' => ['string', 'max:11'],
            'whatsapp_no' => ['nullable', 'max:11'],
            'cnic' => ['required', 'string'],
            'address1' => ['string', 'nullable'],
            'address2' => ['string', 'nullable'],
            'address3' => ['string', 'nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            'create_user' => ['nullable'],
            'passport' => ['required'],
            'business_partner_id' => ['nullable'],
            'company_name' => ['nullable'],

        ]);
        if ($partner_validator->fails()) {
            dd($partner_validator->errors());
            return response()->json(['errors' => $partner_validator->errors()], 400);
        }

        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['created_by'] = auth()->user()->id;

        $payload['partner_data'] = $partner_data;
        // $partner = Partner::create($data);

        if ($request['create_user'] == 1) {
            // Validate the user data
            $user_validator = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'email' => ['required', 'email', Rule::unique('users'), Rule::unique('partners')],
                'password' => ['required', 'min:8', 'confirmed'],
                'password_confirmation' => ['required', 'min:8'],

            ]);

            // Check if validation fails
            if ($user_validator->fails()) {
                // Return validation errors
                dd($user_validator->errors());
                return response()->json(['errors' => $user_validator->errors()], 400);
            }
            $user_data = $user_validator->validated();
            $payload['created_by'] = auth()->user()->id;
            $payload['image'] = 'profile_images/default/default.jpeg';
            $payload['user_data'] = $user_data;

        }
        //dd($payload);
        if ($partner_data['partner_type'] == 'customer') {
            Partner::store_customer($payload);
        }
        if ($partner_data['partner_type'] == 'business') {
            Partner::store_business($payload);
        }
        if ($partner_data['partner_type'] == 'employee') {
            Partner::store_employee($payload);
        }
        if ($partner_data['partner_type'] == 'agent') {
            Partner::store_agent($payload);
        }

        $response = [
            'message' => 'Partner created successfully',
            'partner' => $partner_data,
            'user'=>$user_data,
        ];

        return response()->json($response, 201);
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
                'name' => "Partner",
                'link' => route("partners.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("partners.show", $id),
                'active' => true,
            ],
        ];
        $partner = Partner::find($id);

        return response()->json([
            'partner' => $partner,
            'breadcrumbs' => $breadcrumbs], 200);
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
                'name'=>"Partner",
                'link'=>route("partners.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("partners.edit",$id),
                'active'=>true,
            ]
        ];
        $partner = Partner::find($id);
        // $business_partner = Partner::where('partner_type','business')->get();
        // $form_type = 'all';


        return response()->json([
            'partner' => $partner,
            'breadcrumbs' => $breadcrumbs], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Partner $partner
     * *
     */
    public function api_update(Request $request, Partner $partner,)
    {
        $payload = [];

        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            'name' => ['required','string'],
            'partner_type' => ['nullable'],
            'email' => ['required'],
            'phone_no' => ['string','max:11'],
            'whatsapp_no' => ['nullable','max:11'],
            'cnic' => ['required','string'],
            'address1' => ['string','nullable'],
            'address2' => ['string','nullable'],
            'address3' => ['string','nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            'create_user'=> ['nullable'],
            'passport'=>['required'],
            'business_partner_id'=> ['nullable'],
            'company_name'=> ['nullable'],

            ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return response()->json(['errors' => $partner_validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['updated_by'] = auth()->user()->id;
        $payload['partner_data'] = $partner_data;

        if ($partner_data['create_user'] == 1) {
            // Validate the user data
            $user_validator = Validator::make($request->all(), [
                'name' => ['required','string'],
                'email' => ['required'],
                //  'email', Rule::unique('users')->ignore($partner->user->id), Rule::unique('partners')->ignore($partner->id)],
                'password' => ['required','min:8','confirmed'],
                'password_confirmation' => ['required','min:8'],

            ]);

            // Check if validation fails
            if ($user_validator->fails()) {
                // Return validation errors
                return response()->json(['errors' => $user_validator->errors()], 400);
            }
            $user_data = $user_validator->validated();
            $payload['user_data'] = $user_data;

        }
        $payload['partner'] = $partner;
        if($partner_data['partner_type']=='customer'){
            Partner::update_customer($payload);
        }
        if($partner_data['partner_type']=='business'){
            Partner::update_business($payload);
        }
        if($partner_data['partner_type']=='employee'){
            Partner::update_employee($payload);
        }
        // if($partner_data['partner_type']=='agent'){
        //     Partner::update_agent($payload);
        // }
        //dd($payload);
        // Partner::update_partner($payload);

        //$partner->update($parter_data);

        $response = [
            'message' => 'Partner updated successfully',
            // 'partner'=>$partner,
            'partner' => $partner_data,
            // 'user'=>$user_data,
        ];

        return response()->json($response, 201);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        $partner = Partner::find($id)->delete();

        return redirect()->route('partners.index')
            ->with('success', 'Partner deleted successfully');
    }
}
