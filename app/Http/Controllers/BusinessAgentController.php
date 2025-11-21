<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AgentExport;


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
class BusinessAgentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $role_module_id = 13;


    public $my_companies;

    function __construct(){
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });

    }
    static $ignores = ['search'=>true];

    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"Business Agent",
                'link'=>route("business_agents.index"),
                'active'=>true,
            ]
        ];

        $user = auth()->user();

        $company = $user->companies->first();

        // if (!$company) {
        //     return redirect()->route('')->with('error', 'No associated company found.');
        // }
        $companyId = auth()->user()->active_company() ?? null;

        $query = $request->input('query');
        $perPage = $request->input('perPage', 10);

        $partners = Partner::when($companyId, function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        })->checkGlobal(13)
        ->where('actor_id', 4) ->whereHas('users', function($q) {
            $q->where('permission', 1);
        })
        ->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('name', 'ILIKE', '%' . $query . '%')
                ->orWhere('email', 'ILIKE', '%' . $query . '%')
                ->orWhere('phone_no', 'ILIKE', '%' . $query . '%')
                ->orWhere('city', 'ILIKE', '%' . $query . '%')
                ->orWhere('country', 'ILIKE', '%' . $query . '%');
            });
        })->orderBy('created_at', 'desc')->paginate($perPage);


        // $permissions = auth()->user()->get_user_role_session_permissions();
        // $target_method = ''
        // $permission = $permissions->filter(function($value, $key) use($target_method){
        //     $module =  $value->role_module_functions->filter(function($value, $key)  use($target_method){
        //         return $value->method == $target_method;
        //     });
        //     return $module->first();
        // })->first();
        // dd($permissions->where('role_permission_type.is_read',1));

        return view('business-agent.index', compact('partners','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $partners->perPage());
    }
    public function search()
    {
        // dd('tatata');
        $company_id = auth()->user()->active_company();

        $search = $search = $_GET['term']??'';
        if($search){
            $agent = Partner::where('actor_id',4)
            ->where('company_id', $company_id)
            ->where('name', 'LIKE', '%' . $search . '%')->get();
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
                'name'=>"Business Agent",
                'link'=>route("business_agents.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("business_agents.create"),
                'active'=>true,
            ]
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
    public function store(Request $request)
    {
        $payload = [];

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


        // Validate the request data
        if ($partner_validator->fails()) {
            //dd($partner_validator->errors());
            return back()->with('errors', $partner_validator->errors())->withInput();

            // return back()->with('errors', $partner_validator->errors());
        }

        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['created_by'] = auth()->user()->id;
        $partner_data['source'] = 'manual';

        $partner_data['actor_id'] = 4; // 4 is actor_id for agent

        $payload['partner_data'] = $partner_data;
        // $partner = Partner::create($data);

        if ($request['create_user'] == 1) {
            // Validate the user data
            $user_validator = Validator::make($request->all(), [
                'name' => ['required','string'],
                'email' => ['required','email',Rule::unique('users'),Rule::unique('partners'),
                ],
                // 'image'=>['nullable','image','max:2048'],
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



        return redirect()->route('business_agents.index')->with('success', 'Business created successfully.');
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
                'name'=>"Business Agent",
                'link'=>route("business_agents.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("business_agents.show",$id),
                'active'=>true,
            ]
        ];

        $partner = Partner::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);

        return view('business-agent.show', compact('partner','breadcrumbs'));
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
                'name'=>"Business Agent",
                'link'=>route("business_agents.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("business_agents.edit",$id),
                'active'=>true,
            ]
        ];
        $partner = Partner::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'notall';


        return view('business-agent.edit', compact('partner','breadcrumbs', 'form_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Partner $partner
     * *
     */
    public function update(Request $request,$partner)
    {
        $payload = [];
        $partner = Partner::find($partner);

        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            'name' => ['required','string'],
            // 'partner_type' => ['required'],
            'email' => ['required'],
            'phone_no' => ['string',],
            'whatsapp_no' => ['required',],
            'prefix_whatsapp'=>['required'],
            'prefix_phone'=>['nullable'],
            'cnic' => ['required','string'],
            'address1' => ['string','nullable'],
            'address2' => ['string','nullable'],
            'address3' => ['string','nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            // 'create_user'=> ['required'],
            'passport'=>['nullable'],
            // 'business_partner_id'=> ['nullable'],
            'company_name'=> ['required'],
            'company_id'=>['nullable'],
            // 'employee_type'=>['nullable'],


            ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return back()->with('errors', $partner_validator->errors());
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

        return redirect()->route('business_agents.index')
            ->with('success', 'Business updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $partner = Partner::find($id)->delete();

        return redirect()->route('business_agents.index')
            ->with('success', 'Business deleted successfully');
    }

    public function export(){
        return Excel::download(new AgentExport, 'agents.xlsx');
    }
    // public function register_form()
    // {

    //     $breadcrumbs = [
    //         [
    //             'name'=>"Business Agent",
    //             'link'=>route("business_agents.index"),
    //             'active'=>false,
    //         ],
    //         [
    //             'name'=>"Register",
    //             'link'=>route("register_agent.create"),
    //             'active'=>true,
    //         ]
    //     ];
    //     // $partner = new Partner();
    //     // $business_partner = Partner::where('partner_type','business')->get();
    //     $form_type = 'all';
    //     return view('business-agent.agent_register.create', compact('breadcrumbs', 'form_type'));
    // }


}
