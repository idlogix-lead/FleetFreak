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
class BusinessAgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $role_module_id = 13;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }

    static $ignores = ['search' => true,
        // 'api_index' => true,
        // 'api_store' => true,
        // 'api_update' => true,
        // 'api_destroy'=>true,

    ];

    public function api_index(Request $request)
    {
        $breadcrumbs = [
            [
                'name' => "Business Agent",
                'link' => route("business_agents.index"),
                'active' => true,
            ],
        ];

        $query = $request->input('query');
        $user=auth()->user();
        $company=auth()->user()->active_company();

        // $partners = Partner::checkGlobal(13)->where('actor_id', 4)->where('company_id', $company)
        //     ->when($query, function ($queryBuilder) use ($query) {
        //         $queryBuilder->where('name', 'like', '%' . $query . '%');
        //     })->get(); // Fetch all records without pagination

        $partners = Partner::checkGlobal(13)->where('actor_id', 4)->where('company_id', $company)->get();

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
            'partners' => $partners,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function search()
    {
        // dd('tatata');
        $search = $search = $_GET['term'] ?? '';
        if ($search) {
            $agent = Partner::where('actor_id', 4)->where('name', 'ILIKE', '%' . $search . '%')->get();
            return response()->json($agent);
        }

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
                'name' => "Business Agent",
                'link' => route("business_agents.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("business_agents.create"),
                'active' => true,
            ],
        ];
        // $partner = new Partner();
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'all';
        return view('business-agent.create', compact('breadcrumbs', 'form_type'));
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
        //  dd($request);
        $partner_validator = Validator::make($request->all(), [
               'name' => ['required','string'],
                // 'partner_type' => ['required'],
                'email' => ['required','email',Rule::unique('users'),Rule::unique('partners')],
                'phone_no' => ['nullable'],
                'whatsapp_no' => ['required',],
                'prefix_whatsapp'=>['required'],
                'prefix_phone'=>['nullable'],
                'cnic' => ['required','string'],
                'address1' => ['string','nullable'],
                'address2' => ['string','nullable'],
                'address3' => ['string','nullable'],
                'city' => ['nullable'],
                'country' => ['nullable'],
                'create_user'=> ['required'],
                'passport'=>['nullable'],
                // 'business_partner_id'=> ['nullable'],
                'company_name'=> ['required'],
                'company_id' => ['nullable'],
                // 'employee_type'=>['nullable'],

        ]);
        // dd($partner_validator);
        // Validate the request data
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            // return back()->with('errors', $partner_validator->errors())->withInput();
            return response()->json(['errors' => $partner_validator->errors()], 400);

            // return back()->with('errors', $partner_validator->errors());
        }

        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['created_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 4; // 4 is actor_id for agent
        $partner_data['source'] = 'manual';

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
            $payload['created_by'] = auth()->user()->id;
            // $payload['image'] = 'profile_images/default/default.jpeg';
            $payload['user_data'] = $user_data;

        }
        //dd($payload);
        // if($partner_data['partner_type'] =='customer'){
        //     Partner::store_customer($payload);
        // }
        // dd($payload);

        Partner::store_business($payload);

        // if($partner_data['partner_type'] =='employee'){
        //     // dd($payload);
        //     Partner::store_employee($payload);
        // }

        // return redirect()->route('business_agents.index')->with('success', 'Business created successfully.');
        return response()->json(['partner_data' => $partner_data, 'user_data' => $user_data, 'message' => 'businesspartner create successfully'],200);
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
                'name' => "Business Agent",
                'link' => route("business_agents.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("business_agents.show", $id),
                'active' => true,
            ],
        ];
        $user=auth()->user();
        $company=auth()->user()->active_company();
        $partner = Partner::checkGlobal(13)->where('company_id', $company)->find($id);

        // return view('business-agent.show', compact('partner', 'breadcrumbs'));
        return response()->json(['partner' => $partner]);
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
                'name' => "Business Agent",
                'link' => route("business_agents.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("business_agents.edit", $id),
                'active' => true,
            ],
        ];
        $user=auth()->user();
        $company=auth()->user()->active_company();
        $partner = Partner::checkGlobal(13)->where('company_id',$company)->find($id);
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'notall';

        return view('business-agent.edit', compact('partner', 'breadcrumbs', 'form_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Partner $partner
     * *
     */
    public function api_update(Request $request, $partner)
    {
        $payload = [];
        $partner = Partner::find($partner);

        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            // 'partner_type' => ['required'],
            'email' => ['required'],
            'phone_no' => ['string'],
            'whatsapp_no' => ['nullable'],
            'cnic' => ['required', 'string'],
            'address1' => ['string', 'nullable'],
            'address2' => ['string', 'nullable'],
            'address3' => ['string', 'nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            // 'create_user'=> ['required'],
            'passport' => ['nullable'],
            // 'business_partner_id'=> ['nullable'],
            'company_name' => ['required'],
            // 'employee_type'=>['nullable'],

        ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return response()->json(['errors' => $partner_validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['updated_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 4; // 4 is actor_id for agent
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

        Partner::update_business($payload);
        // dd($payload);

        // if($partner_data['partner_type']=='employee'){
        //     Partner::update_employee($payload);
        // }
        // if($partner_data['partner_type']=='agent'){
        //     Partner::update_agent($payload);
        // }
        //dd($payload);
        // Partner::update_partner($payload);

        //$partner->update($parter_data);

        // return redirect()->route('business_agents.index')
        //     ->with('success', 'Business updated successfully');

        return response()->json(['partner_data' => $partner_data, 'message' => 'businesspartner create successfully']);

    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        $user=auth()->user();
        $company=auth()->user()->active_company();
        $partner = Partner::where('company_id',$company)->find($id);
        if ($partner) {
          $partner->delete();
            return response()->json(['success' => 'Business deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Business not found'], 404);

        }

    }

}
