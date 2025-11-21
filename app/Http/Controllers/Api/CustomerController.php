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
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $role_module_id = 12;
    // ignored permission functions
    static $ignores = ['partner_dropdown' => true,
        // 'api_index' => true,
        // 'api_store' => true,
        // 'api_update' => true,
        // 'api_destroy'=>true,
    ];

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }
    public function partner_dropdown($type, $business_partner_id)
    {
        //dd($type,$business_partner_id);
        $search = $_GET['q'] ?? '';
        if ($search) {
            // dd($search);
            $partners = Partner::where('partner_type', $type)
                ->where('business_partner_id', $business_partner_id)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'ILIKE', '%' . $search . '%')
                        ->orWhere('cnic', 'ILIKE', '%' . $search . '%')
                        ->orWhere('passport', 'ILIKE', '%' . $search . '%');
                })
                ->get();
            return response()->json($partners, 200);
        }
    }

    public function api_index()
    {
        // $breadcrumbs = [
        //     [
        //         'name' => "Customer",
        //         'link' => route("customers.index"),
        //         'active' => true,
        //     ],
        // ];

        // $user = auth()->user();

        // $userPartnerId = $user->partner_id;

        // if (auth()->user()->actor_id == 4) {
        //     $partners = Partner::where('business_partner_id', $userPartnerId)
        //         ->whereIn('actor_id', [6])
        //         ->orderBy('created_at', 'desc')
        //         ->get();
        //     // ->paginate();
        // } else {
        //     $partners = Partner::whereIn('actor_id', [6, 8])
        //         ->orderBy('created_at', 'desc')
        //         ->get();
        //     // ->paginate();
        // }

            $company = auth()->user()->active_company();
            // dd($company);
            $partners = Partner::where('company_id', $company)
                ->whereIn('actor_id', [6,8])
                ->orderBy('created_at', 'desc')
                ->get();
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

        return response()->json(['partners' => $partners]);
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
                'name' => "Customer",
                'link' => route("customers.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("customers.create"),
                'active' => true,
            ],
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


      /**
    //  * Send a notification using Firebase Cloud Messaging (FCM)
    //  *
    //  * @param string $token
    //  * @param string $title
    //  * @param string $body
    //  */
    // protected function sendNotification($token, $title, $body)
    // {
    //     $url = 'https://fcm.googleapis.com/fcm/send';
    //     $serverKey = env('FIREBASE_SENDER_KEY');
    //     $data = [
    //         "to" => $token,
    //         "notification" => [
    //             "title" => $title,
    //             "body" => $body,
    //         ],
    //     ];
    //     $headers = [
    //         'Authorization: key=' . $serverKey,
    //         'Content-Type: application/json',
    //     ];

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    //     $result = curl_exec($ch);
    //     if ($result === false) {
    //         die('FCM Send Error: ' . curl_error($ch));
    //     }
    //     curl_close($ch);

    //     return $result;
    // }

    public function api_store(Request $request)
    {
        $payload = [];

        $partner_validator = Validator::make($request->all(), [
            'name' => ['required','string'],
           
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
             // 'partner_type' => ['required'],
            // 'email' => ['required','email', Rule::unique('users', 'email'),Rule::unique('partners', 'email')],

            // 'company_name'=> ['nullable'],
            // 'employee_type'=>['nullable'],

        ]);

        // Validate the request data
        if ($partner_validator->fails()) {
            //dd($partner_validator->errors());
            return response()->json(['errors' => $partner_validator->errors()], 400);
        }
        $authenticatedUser = auth()->user();

        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['created_by'] = auth()->user()->id;
        // $partner_data['business_partner_id'] = $authenticatedUser->partner_id;
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
        // $this->sendFirebaseNotification(
        //     $authenticatedUser->firebase_token,
        //     'New Customer Created',
        //     'A new customer has been created.'
        // );

        return response()->json(['message' => 'success', 'data' => $partner_data]);
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
                'name' => "Customer",
                'link' => route("customers.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("customers.show", $id),
                'active' => true,
            ],
        ];
        $partner = Partner::checkGlobal(12)->find($id);

        return response()->json(['partner' => $partner, 'breadcrumbs' => $breadcrumbs]);
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
                'name' => "Customer",
                'link' => route("customers.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("customers.edit", $id),
                'active' => true,
            ],
        ];
        $partner = Partner::checkGlobal(12)->find($id);
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'all';

        return view('customer.edit', compact('partner', 'breadcrumbs', 'form_type'));
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
            'name' => ['required', 'string'],
            // 'partner_type' => ['nullable'],
            'email' => ['nullable'],
            'phone_no' => ['string'],
            'whatsapp_no' => ['nullable'],
            'cnic' => ['required', 'string'],
            'address1' => ['string', 'nullable'],
            'address2' => ['string', 'nullable'],
            'address3' => ['string', 'nullable'],
            'city' => ['nullable'],
            'country' => ['nullable'],
            'create_user' => ['nullable'],
            'passport' => ['required'],
            // 'business_partner_id' => ['nullable'],
            // 'company_name'=> ['nullable'],
            // 'employee_type'=>['nullable'],

        ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return response()->json(['errors' => $partner_validator->errors()], 400);
        }

        $authenticatedUser = auth()->user();
        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['updated_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 6;
        $partner_data['business_partner_id'] = $authenticatedUser->partner_id;
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

        return response()->json(['message' => 'success', 'partner_data' => $partner_data]);

    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        $partner = Partner::find($id)->delete();

        // return redirect()->route('customers.index')
        //     ->with('success', 'Customer deleted successfully');
        return response()->json(['success' =>'customer delete successfully']);
    }
    public function create_customer_from_order(Request $request)
    {
        $data = json_decode($request->input('data'), true);
        // dd($data);
        $payload = [];
        $customer_validator = Validator::make($data, [
            //
            'name' => ['required', 'string'],
            // 'partner_type' => ['required',],
            'email' => ['required'],
            'phone_no' => ['string'],
            'whatsapp_no' => ['nullable'],
            'cnic' => ['required', 'string'],
            'address1' => ['string', 'nullable'],
            'address2' => ['string', 'nullable'],
            'address3' => ['string', 'nullable'],
            'city' => ['string'],
            'country' => ['string'],
            // create_user'=> ['nullable',],
            'passport' => ['required'],
            'business_partner_id' => ['nullable'],

        ]);
        if ($customer_validator->fails()) {
            // dd($customer_validator->errors());
            return back()->with('errors', $customer_validator->errors());
        }
        $partner_data = $customer_validator->validated();
        $partner_data['created_by'] = auth()->user()->id;
        $partner_data['actor_id'] = 6;
        $payload['partner_data'] = $partner_data;
        // dd($payload);

        $partners = Partner::store_customer($payload);
        //return redirect()->back()->with('success', 'Partner deleted successfully');
        // dd($partners);
        if ($partners) {
            return response()->json($partners, 200
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

}
