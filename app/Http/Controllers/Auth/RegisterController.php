<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\Actor;
use App\Models\Table;
use App\Models\Event;
use App\Models\RolePermissionTypeFunction;
use App\Models\RoleModule;
use App\Models\RoleModuleActors;
use App\Models\RolePermissionType;
use App\Models\RolePermission;
use App\Models\SidebarGroups;
use App\Models\SidebarItems;
use App\Models\Company;
use App\Models\UserCompany;
use App\Models\Account;
use App\Models\AccountType;
// temp
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterNotification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // public function register(Request $request)
    // {

    //     $email_validator = Validator::make($request->all(), [

    //         'email' => ['required', 'email', Rule::unique('users')],
    //     ]);
    //     // Validate the request data
    //     // Validate the request data
    //     if ($email_validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $email_validator->errors()->first('email'),
    //         ]);
    //     }

    //     // If validation passes, return success
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Email is valid',
    //         'email' => $request->email,
    //     ]);

    //     // if($agent_data){
    //     //     return view('auth.company_details', ['email' => $agent_data['email']]);
    //     // }

    // }
    public function register(Request $request)
    {

        $email_validator = Validator::make($request->all(), [
            'email' => ['required', 'email', Rule::unique('users')],
        ]);
        if($email_validator->fails()) {
            return back()->withInput()->withErrors($email_validator->errors());
        }
        $data = $email_validator->validated();

        $actor_id = 2;

        $password = Str::random(8);
        $client = Client::updateOrCreate([
            'name' => $data['email'],
            // 'user_id' => $adminUser->id,
        ]);
        $role = Role::register_company_role($actor_id, $client->id);
        $data = [
            'email' => $data['email'],
            'name' => $data['email'],
            'phone_no1'=> null,
            'phone_no2'=> null,
            'description' => null,
            'image' => null,
            'password' => $password,
            'role_id'=> $role->id,
            'client_id'=> $client->id,
            'is_company_admin'=> 1,
        ];
        $payload = [
            'data' => $data,
            'actor_id'=> $actor_id,
            'created_by'=> null,
        ];

        $user = User::store_user($payload);


        Client::where('id', $client->id)->update([
            // 'name' => $data['email'],
            'user_id' => $user->id,
        ]);

        self::createRegisterNotificationEvent($user, $user->id, $password);
        return redirect()->route('login')->with('success', 'Registered Successfuly, Wait for Email!');
    }

    public static function createRegisterNotificationEvent($user, $auth_user_id, $password){
        $table = Table::where('id', 40)->first();



        // $modelClass = 'App\\Models\\' . $table->model_name;
        // $vehicle = $modelClass::where('id', $data['vehicle_id'])->first();

        // $days = $vehicle->maintenance_interval_days;
        // $km = $vehicle->maintenance_oilchange_interval_km;



            // $date = Carbon::now()->addDays($days)->toDateString();
            $title = "Registerd Successfully!";
            $description = json_encode([
                'user' => $user,
            ]);


            $notifications = [];
            $notifications[] = [
                'receiver_id' => $user->id,
                'sender_id' => $auth_user_id,
                // 'calendar' => 1,
                // 'sms' => 1,
                'email' => 1,
                // 'whatsapp' => 1,
                // 'fcm_mobile_push' => 1,
                // 'fcm_web_push' => 1,
            ];

            // dd($date,$notifications,$data['vehicle_id']);
            Event::createEvent($table->id, $user->id, $user->email, 'registered', $title, $description, $password, null, date('Y-m-d'), $notifications,$auth_user_id);


    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'name' => ['required','string'],
    //         // 'partner_type' => ['required'],
    //         'email' => ['required','email',Rule::unique('users'),Rule::unique('partners')],
    //         'phone_no' => ['string','nullable'],
    //         'whatsapp_no' => ['required',],
    //         'prefix_whatsapp'=>['required'],
    //         'prefix_phone'=>['nullable'],
    //         'iata_no'=>['nullable'],
    //         'address1' => ['string','nullable'],
    //         'govt_license_no'=>['required'],
    //         'city' => ['nullable'],
    //         'country' => ['nullable'],
    //         'create_user'=> ['required'],

    //         // 'business_partner_id'=> ['nullable'],
    //         'company_name'=> ['required'],
    //         'password' => ['required','min:8','confirmed'],
    //         'password_confirmation' => ['required','min:8'],
    //         // 'employee_type'=>['nullable'],
    //         // 'name' => ['required', 'string', 'max:255'],
    //         // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         // 'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    // }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
