<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DriverExport;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
/**
 * Class PartnerController
 * @package App\Http\Controllers
 */
class DriverController extends Controller
{
    static $ignores = [];

    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $role_module_id = 11;
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
                'name'=>"Driver",
                'link'=>route("drivers.index"),
                'active'=>true,
            ]
        ];
        $user = auth()->user();

        $company = $user->companies->first();
        $companyId = auth()->user()->active_company() ?? null;

        // $query = $request->input('query');
        // // dd($query);
        // $partners = Partner::checkGlobal(11)->where('actor_id',5)->when($companyId, function ($query) use ($companyId) {
        //     return $query->where('company_id', $companyId);
        //     })->when($query, function ($queryBuilder) use ($query) {
        //         $queryBuilder->where(function ($q) use ($query) {
        //             $q->where('name', 'like', '%' . $query . '%')
        //             ->orWhere('email', 'like', '%' . $query . '%')
        //             ->orWhere('phone_no', 'like', '%' . $query . '%')
        //             ->orWhere('city', 'like', '%' . $query . '%')
        //             ->orWhere('country', 'like', '%' . $query . '%');
        //         });
        //     })->orderBy('created_at', 'desc')->paginate();

        $query = strtolower($request->input('query')); // Convert query to lowercase
        $perPage = $request->input('perPage', 10);
        // dd($perPage);

        // dd($query);
        $partners = Partner::checkGlobal(11)
            ->where('actor_id', 5)
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('company_id', $companyId);
            })
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%' . $query . '%'])
                        ->orWhereRaw('LOWER(email) LIKE ?', ['%' . $query . '%'])
                        ->orWhereRaw('LOWER(phone_no) LIKE ?', ['%' . $query . '%'])
                        ->orWhereRaw('LOWER(city) LIKE ?', ['%' . $query . '%'])
                        ->orWhereRaw('LOWER(country) LIKE ?', ['%' . $query . '%'])
                        ->orWhereRaw('LOWER(cnic) LIKE ?', ['%' . $query . '%'])
                        ->orWhereRaw('LOWER(driver_license) LIKE ?', ['%' . $query . '%']);

                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);



        return view('driver.index', compact('partners','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $partners->perPage());
    }

    public function driver_search()
    {
        // driver actor id is 5;
        $search = $search = $_GET['term']??'';
        if($search){
            $agent = Partner::where('actor_id',5)->where('company_id',auth()->user()->active_company())->where('name', 'LIKE', '%' . $search . '%')->get();
            // dd($agent);
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
                'name'=>"Driver",
                'link'=>route("drivers.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("drivers.create"),
                'active'=>true,
            ]
        ];
        // $partner = new Partner();
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'all';
        return view('driver.create', compact('breadcrumbs', 'form_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function store(Request $request): RedirectResponse
    {
        $payload = [];
        // dd($request);
        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            'name' => ['required','string'],
            // 'partner_type' => ['required'],
            'email' => ['required','email',Rule::unique('users'),Rule::unique('partners')],
            'phone_no' => ['required'],
            'whatsapp_no' => ['required'],
            'prefix_whatsapp'=>['required'],
            'prefix_phone'=>['nullable'],
            'prefix_emergency_contact1'=>['nullable'],
            'prefix_emergency_contact2'=>['nullable'],
            // 'nic_no'=>['required'],
            'license_country'=>['nullable'],
            'licensee_expiry_date'=>['nullable'],
            'emergency_contact_no1'=>['nullable'],
            'emergency_contact_no2'=>['nullable'],
            'nic_expiry_date'=>['nullable'],
            'emergency_contact_name'=>['nullable'],



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
            'driver_license'=>['nullable'],

        ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return back()->with('errors', $partner_validator->errors())->withInput();
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
            $user_data['role_id']= Role::where('client_id',auth()->user()->client_id)->where('actor_id',5)->value('id');
            $payload['created_by'] = auth()->user()->id;
            $payload['image'] = 'profile_images/default/default.jpeg';
            $payload['user_data'] = $user_data;

        }
        //dd($payload);
        // if($partner_data['partner_type'] =='customer'){
        //     Partner::store_customer($payload);
        // }
        // if($partner_data['partner_type'] =='business'){
        //     Partner::store_business($payload);
        // }
        // if($partner_data['partner_type'] =='employee'){
            Partner::store_employee($payload);
        // }
        // if($partner_data['partner_type'] =='agent'){
        //     Partner::store_agent($payload);
        // }



        return redirect()->route('drivers.index')->with('success', 'Partner created successfully.');
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
                'name'=>"Drivers",
                'link'=>route("drivers.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("drivers.show",$id),
                'active'=>true,
            ]
        ];
        $partner = Partner::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);

        return view('driver.show', compact('partner','breadcrumbs'));
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
                'name'=>"Drivers",
                'link'=>route("drivers.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("drivers.edit",$id),
                'active'=>true,
            ]
        ];
        $partner = Partner::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'notall';


        return view('driver.edit', compact('partner','breadcrumbs', 'form_type'));
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

        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            'name' => ['required','string'],
            // 'partner_type' => ['nullable'],
            'email' => ['required'],
            'phone_no' => ['required'],
            'whatsapp_no' => ['required'],
            'prefix_whatsapp'=>['required'],
            'prefix_phone'=>['nullable'],
            'prefix_emergency_contact1'=>['nullable'],
            'prefix_emergency_contact2'=>['nullable'],
            // 'nic_no'=>['required'],
            'license_country'=>['nullable'],
            'licensee_expiry_date'=>['nullable'],
            'emergency_contact_no1'=>['nullable'],
            'emergency_contact_no2'=>['nullable'],
            'nic_expiry_date'=>['nullable'],
            'emergency_contact_name'=>['nullable'],
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
            'driver_license'=>['nullable'],


            ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return back()->with('errors', $partner_validator->errors());
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
        // if($partner_data['partner_type']=='employee'){
            Partner::update_employee($payload);
        // }
        // if($partner_data['partner_type']=='agent'){
        //     Partner::update_agent($payload);
        // }
        //dd($payload);
        // Partner::update_partner($payload);

        //$partner->update($parter_data);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $partner = Partner::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'Driver deleted successfully');
    }

    public function export(){
        return Excel::download(new DriverExport, 'drivers.xlsx');
    }


}
