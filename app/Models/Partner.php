<?php

namespace App\Models;

use App\Models\Concerns\ChecksGlobalPermission;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


/**
 * Class Partner
 *
 * @property $id
 * @property $name
 * @property $partner_type
 * @property $email
 * @property $phone_no
 * @property $whatsapp_no
 * @property $cnic
 * @property $address1
 * @property $address2
 * @property $address3
 * @property $city
 * @property $country
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @property User $user
 * @property User[] $users
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Partner extends BaseModel
{
    use BelongsToOrganization, ChecksGlobalPermission;

    // static $rules = [
	// 		'name' => 'required|string',
	// 		'partner_type' => 'required',
	// 		'email' => 'string',
	// 		'phone_no' => 'string',
	// 		'whatsapp_no' => 'string',
	// 		'cnic' => 'required|string',
	// 		'address1' => 'string',
	// 		'address2' => 'string',
	// 		'address3' => 'string',
	// 		'city' => 'string',
	// 		'country' => 'string',
    // ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'employee_type', 'partner_type', 'actor_id', 'business_partner_id', 'company_id',
        'email', 'passport', 'phone_no', 'whatsapp_no', 'prefix_phone', 'prefix_whatsapp', 'cnic',
        'age', 'experience', 'akama', 'company_name',
        'address1', 'address2', 'address3', 'city', 'country',
        'iata_no', 'govt_license_no', 'source', 'is_system', 'permission_status', 'pass_key',
        'created_by', 'updated_by',
        'nic_expiry_date', 'license_country', 'licensee_expiry_date',
        'emergency_contact_no1', 'emergency_contact_no2', 'emergency_contact_name',
        'prefix_emergency_contact1', 'prefix_emergency_contact2',
        'partner_loc_id', 'price_list_id', 'driver_license',
        'created_at', 'updated_at',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'partner_id', 'id');
    }

    public function orders_customer()
    {
        return $this->hasMany(Order::class, 'customer_partner_id');
    }
    public function orders_agent()
{
    return $this->hasMany(Order::class, 'business_partner_id');
}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'partner_id', 'id');
    }
    public function actors()
    {
        return $this->belongsTo(\App\Models\Actor::class, 'actor_id','id');
    }
    public function partnerLocation()
    {
        return $this->belongsTo(\App\Models\PartnerLocation::class, 'partner_loc_id','id');
    }

    public function comapny()
    {
        return $this->belongsTo(Company::class);

    }
    static function store_customer($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $partners = Partner::create([
            'name' => $partner_data['name'],
            // 'partner_type' => $partner_data['partner_type'],
            'email' => $partner_data['email'],
            'phone_no' => $partner_data['phone_no'] ?? null,
            'whatsapp_no' => $partner_data['whatsapp_no'],
            'prefix_whatsapp'=> $partner_data['prefix_whatsapp'],
            'prefix_phone'=> $partner_data['prefix_phone'] ?? null,

            'cnic' => $partner_data['cnic'],
            'address1' => $partner_data['address1'],
            'address2' => $partner_data['address2']?? null,
            'address3' => $partner_data['address3']?? null,
            'city' => $partner_data['city'],
            'country' => $partner_data['country'],
            'created_by'=>$partner_data['created_by'],
            'passport'=>$partner_data['passport'],
            'business_partner_id'=> $partner_data['business_partner_id'],
            'actor_id'=> $partner_data['actor_id'],
            'company_id'=> $partner_data['company_id']??null


        ]);
        return $partners;

    }

    static function store_walkin_customer($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $user = Auth::user();
        $company_id = auth()->user()->active_company() ??  null;
        // dd($company_id);

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Invalid Driver'], 401);
        }

        // Get the driver's partner ID
        $driverPartnerId = $user->partner_id;

        $partners = Partner::create([
            'name' => $partner_data['name'],
            // 'partner_type' => $partner_data['partner_type'],
            // 'email' => $partner_data['email'],
            'actor_id' => $partner_data['actor_id'],

            'phone_no' => $partner_data['phone_no'],
            'prefix_phone' => $partner_data['prefix_phone'],

            'whatsapp_no' => $partner_data['whatsapp_no'],
            'prefix_whatsapp' => $partner_data['prefix_whatsapp'],
            'cnic' => $partner_data['cnic'] ?? null,
            // 'address1' => $partner_data['address1'],
            // 'address2' => $partner_data['address2'],
            // 'address3' => $partner_data['address3'],
            // 'city' => $partner_data['city'],
            // 'country' => $partner_data['country'],
            'created_by'=>$partner_data['created_by'],
            'passport'=>$partner_data['passport']?? null,
            'business_partner_id'=> $driverPartnerId ,
            'company_id'=>$company_id

        ]);
        return $partners;

    }
    static function store_business($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $partners = Partner::create([
            'name' => $partner_data['name'],
            // 'partner_type' => $partner_data['partner_type'],
            'company_name'=>$partner_data['company_name'],
            'email' => $partner_data['email'],
            'phone_no' => $partner_data['phone_no'],
            'whatsapp_no' => $partner_data['whatsapp_no'],
            'prefix_whatsapp'=>$partner_data['prefix_whatsapp'],
            'prefix_phone'=>$partner_data['prefix_phone']?? null,
            'cnic' => $partner_data['cnic'],
            'address1' => $partner_data['address1'],
            'address2' => $partner_data['address2']?? null,
            'address3' => $partner_data['address3']?? null,
            'city' => $partner_data['city'],
            'country' => $partner_data['country'],
            'created_by'=>$partner_data['created_by'],
            'passport'=>$partner_data['passport']?? null,
            'actor_id'=>$partner_data['actor_id'],
            'source'=>$partner_data['source'],
            'company_id'=>auth()->user()->active_company()
            //'business_partner_id'=> $partner_data['business_partner_id'],

        ]);
        if(isset($user_data)){
            //dd($user_data);
          $user=  User::create([
            'name' => $user_data['name'],
            'email' => $user_data['email'],
            'image'=> 'profile_images/default/default.jpeg'??null,
            'partner_id'=> $partners->id,
            'actor_id'=> $partner_data['actor_id'],
            'role_id'=> Role::where('client_id',auth()->user()->client_id)->where('actor_id',4)->value('id'),
            'flag'=>1,
            'password' => Hash::make($user_data['password']),
            'active_company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->client_id
            ]);
          UserCompany::create([
           'user_id'=>$user->id,
           'company_id' => auth()->user()->active_company(),
          ]);
        }
    }
    static function store_employee($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        // Shared by drivers and employees. The prefix / NIC / licence / emergency-contact fields only
        // exist on the driver form, so they default to null (all nullable columns) for employees.
        $data = [
            'name' => $partner_data['name'],
            // 'partner_type' => $partner_data['partner_type'],
            'email' => $partner_data['email'],
            'phone_no' => $partner_data['phone_no'],
            'whatsapp_no' => $partner_data['whatsapp_no'],
            'prefix_whatsapp' => $partner_data['prefix_whatsapp'] ?? null,
            'prefix_phone' => $partner_data['prefix_phone']?? null,
            'prefix_emergency_contact1' => $partner_data['prefix_emergency_contact1'] ?? null,
            'prefix_emergency_contact2' => $partner_data['prefix_emergency_contact2'] ?? null,
            // 'nic_no' => $partner_data['nic_no'],
            'nic_expiry_date' => $partner_data['nic_expiry_date'] ?? null,
            'license_country' => $partner_data['license_country'] ?? null,
            'licensee_expiry_date' => $partner_data['licensee_expiry_date'] ?? null,
            'emergency_contact_no1' => $partner_data['emergency_contact_no1'] ?? null,
            'emergency_contact_no2' => $partner_data['emergency_contact_no2'] ?? null,
            'emergency_contact_name' => $partner_data['emergency_contact_name'] ?? null,
            'cnic' => $partner_data['cnic'],


            'address1' => $partner_data['address1'],
            'address2' => $partner_data['address2']?? null,
            'address3' => $partner_data['address3']?? null,
            'city' => $partner_data['city'],
            'country' => $partner_data['country'],
            'created_by'=>$partner_data['created_by'],
            // 'company_name'=>$partner_data['company_name'],
            'passport'=>$partner_data['passport'],
            // 'business_partner_id'=> $partner_data['business_partner_id'],
            // 'employee_type'=> $partner_data['employee_type'],
            'age'=>$partner_data['age'] ?? null,
            'experience'=>$partner_data['experience'] ?? null,
            'akama'=>$partner_data['akama'] ?? null,
            'actor_id'=>$partner_data['actor_id'],
            'driver_license'=>$partner_data['driver_license'] ?? null,
            'company_id'=>auth()->user()->active_company()


        ];
        if (isset($call_from_employee_controller)) {
            $data['employee_type'] = $partner_data['employee_type'];

        }

        $partners = Partner::create($data);
        $company = auth()->user()->active_company();
        if($partner_data['actor_id']==5){
            Event::createEvent(22,$partners->id,$partners->name,'created','driver is created',null,null,$company);
        } else {
            Event::createEvent(22,$partners->id,$partners->name,'created','employee is created',null,null,$company);
        }


        if(isset($user_data)){
            //dd($user_data);
            $user =User::create([
            'name' => $user_data['name'],
            'email' => $user_data['email'],
            'image'=> 'profile_images/default/default.jpeg',
            'partner_id'=> $partners->id,
            'actor_id'=> $partner_data['actor_id'],
            'role_id'=> $user_data['role_id'],
            'password' => Hash::make($user_data['password']),
            'active_company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->client_id

        ]);
            UserCompany::create([
                'user_id' => $user->id,
                'company_id' => auth()->user()->active_company(),
            ]);


        }
    }

    static function update_customer($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        // dd($partner_data);
       $partner->update([
            'name' => $partner_data['name'],
            // 'partner_type' => $partner_data['partner_type'],
            // 'email' => $partner_data['email'],
            'phone_no' => $partner_data['phone_no'],
            'whatsapp_no' => $partner_data['whatsapp_no'],
            'prefix_whatsapp'=> $partner_data['prefix_whatsapp'],
            'prefix_phone'=> $partner_data['prefix_phone'],
            'cnic' => $partner_data['cnic'],
            'address1' => $partner_data['address1'],
            'address2' => $partner_data['address2']?? null,
            'address3' => $partner_data['address3']?? null,
            'city' => $partner_data['city'],
            'country' => $partner_data['country'],
            // 'create_user'=> $partner_data['create_user'],
            'updated_by'=>$partner_data['updated_by'],
            'passport'=>$partner_data['passport'],
            'business_partner_id'=> $partner_data['business_partner_id'],
            'actor_id'=> $partner_data['actor_id'],
            // 'company_id'=> $partner_data['company_id'],

        ]);


    }
    static function update_business($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        // dd($partner);
        $user = auth()->user();

        $company = auth()->user()->active_company() ?? null;

        $partner->update([
            'name' => $partner_data['name'],
            // 'partner_type' => $partner_data['partner_type'],
            'company_name'=>$partner_data['company_name'],
            'email' => $partner_data['email'],
            'phone_no' => $partner_data['phone_no'],
            'whatsapp_no' => $partner_data['whatsapp_no'],
            'prefix_whatsapp' => $partner_data['prefix_whatsapp']?? null,
            'prefix_phone' => $partner_data['prefix_phone']?? null,
            'cnic' => $partner_data['cnic'],
            'address1' => $partner_data['address1'],
            'address2' => $partner_data['address2']?? null,
            'address3' => $partner_data['address3']?? null,
            'city' => $partner_data['city'],
            'country' => $partner_data['country'],
            // 'create_user'=> $partner_data['create_user'],
            'updated_by'=>$partner_data['updated_by'],
            'passport'=>$partner_data['passport']?? null,
            'actor_id'=>$partner_data['actor_id'],
            'company_id'=>$company,
            // 'business_partner_id'=> $partner_data['business_partner_id'],

        ]);

        // if(isset($user_data)){
        //     User::where('partner_id',$partner->id)->update([
        //         'name' => $user_data['name'],
        //         'email' => $user_data['email'],
        //         'password' => $user_data['password'],

        //     ]);
        // }

    }
    static function update_employee($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        // dd($partner_data);
        $user=auth()->user();
        $company=auth()->user()->active_company() ?? null;
       $data = [
            'name' => $partner_data['name'],
            // 'partner_type' => $partner_data['partner_type'],
            'email' => $partner_data['email'],
            'phone_no' => $partner_data['phone_no'],
            'whatsapp_no' => $partner_data['whatsapp_no'],
            'prefix_phone' => $partner_data['prefix_phone']?? null,
            'cnic' => $partner_data['cnic'],
            // 'nic_no' => $partner_data['nic_no'],
            'address1' => $partner_data['address1'],
            'address2' => $partner_data['address2']?? null,
            'address3' => $partner_data['address3']?? null,
            'city' => $partner_data['city'],
            'country' => $partner_data['country'],
            // 'create_user'=> $partner_data['create_user'],
            'updated_by'=>$partner_data['updated_by'],
            'passport'=>$partner_data['passport'],
            // 'business_partner_id'=> $partner_data['business_partner_id'],
            // 'employee_type'=>$partner_data['employee_type'],
            'age'=>$partner_data['age'] ?? null,
            'experience'=>$partner_data['experience'] ?? null,
            'akama'=>$partner_data['akama'] ?? null,
            'actor_id'=>$partner_data['actor_id'],
            'company_id'=>$company,

        ];
        // Shared by drivers and employees. These fields only exist on the driver form, so they are
        // written only when sent; an employee edit (or a request that omits one) keeps the stored value.
        foreach (['prefix_whatsapp', 'prefix_emergency_contact1', 'prefix_emergency_contact2', 'nic_expiry_date',
                  'license_country', 'licensee_expiry_date', 'emergency_contact_no1', 'emergency_contact_no2',
                  'emergency_contact_name', 'driver_license'] as $driverField) {
            if (array_key_exists($driverField, $partner_data)) {
                $data[$driverField] = $partner_data[$driverField];
            }
        }
        if (isset($call_from_employee_controller)) {
            $data['employee_type'] = $partner_data['employee_type'];

        }
        $partner->update($data);


    }
    static function store_vendor($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $partners = Partner::create([
            'name' => $partner_data['name'],
            // 'partner_type' => $partner_data['partner_type'],
            'company_name'=>$partner_data['company_name'],
            'email' => $partner_data['email'],
            'phone_no' => $partner_data['phone_no'],
            'whatsapp_no' => $partner_data['whatsapp_no'],
            'prefix_whatsapp'=>$partner_data['prefix_whatsapp'],
            'prefix_phone'=>$partner_data['prefix_phone']?? null,
            'cnic' => $partner_data['cnic'],
            'address1' => $partner_data['address1'],
            'price_list_id' => $partner_data['price_list_id'],
            // 'address2' => $partner_data['address2']?? null,
            // 'address3' => $partner_data['address3']?? null,
            'city' => $partner_data['city'],
            'country' => $partner_data['country'],
            'created_by'=>$partner_data['created_by'],
            // 'passport'=>$partner_data['passport']?? null,
            'actor_id'=>$partner_data['actor_id'],
            'source'=>$partner_data['source'],
            'company_id'=>auth()->user()->active_company()
            //'business_partner_id'=> $partner_data['business_partner_id'],

        ]);
        if(isset($user_data)){
            //dd($user_data);
          $user=  User::create([
            'name' => $user_data['name'],
            'email' => $user_data['email'],
            'image'=> 'profile_images/default/default.jpeg'??null,
            'partner_id'=> $partners->id,
            'actor_id'=> $partner_data['actor_id'],
            'role_id'=> Role::where('client_id',auth()->user()->client_id)->where('actor_id',10)->value('id'),
            'flag'=>1,
            'password' => Hash::make($user_data['password']),
            'active_company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->client_id
            ]);
          UserCompany::create([
           'user_id'=>$user->id,
           'company_id' => auth()->user()->active_company(),
          ]);
        }
    }
    static function update_vendor($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        // dd($partner);
        $user = auth()->user();

        $company = auth()->user()->active_company() ?? null;

        $partner->update([
            'name' => $partner_data['name'],
            // 'partner_type' => $partner_data['partner_type'],
            'company_name'=>$partner_data['company_name'],
            'email' => $partner_data['email'],
            'phone_no' => $partner_data['phone_no'],
            'whatsapp_no' => $partner_data['whatsapp_no'],
            'prefix_whatsapp' => $partner_data['prefix_whatsapp']?? null,
            'prefix_phone' => $partner_data['prefix_phone']?? null,
            'cnic' => $partner_data['cnic'],
            'address1' => $partner_data['address1'],
            'price_list_id' => $partner_data['price_list_id'],
            // 'address2' => $partner_data['address2']?? null,
            // 'address3' => $partner_data['address3']?? null,
            'city' => $partner_data['city'],
            'country' => $partner_data['country'],
            // 'create_user'=> $partner_data['create_user'],
            'updated_by'=>$partner_data['updated_by'],
            // 'passport'=>$partner_data['passport']?? null,
            'actor_id'=>$partner_data['actor_id'],
            'company_id'=>$company,
            // 'business_partner_id'=> $partner_data['business_partner_id'],

        ]);

        // if(isset($user_data)){
        //     User::where('partner_id',$partner->id)->update([
        //         'name' => $user_data['name'],
        //         'email' => $user_data['email'],
        //         'password' => $user_data['password'],

        //     ]);
        // }

    }
    static function BusinessPartnerDropdownAgent(){
        $user=auth()->user();
        $company=auth()->user()->active_company() ?? null;

        if(auth()->user()->actor_id == 2){
            // return Partner::where('actor_id',4)
            return Partner::whereIn('actor_id',[4])
            ->whereHas('users')
            ->when($company, function($query) use ($company) {
                $query->where('company_id', $company);
            })
            ->get();
        }
        else{
            return Partner::when(auth()->user()->partner->actor_id == 4, function($query){
                $query
                // ->where('partner_type', 'business')
                ->where('id',auth()->user()->partner_id);
            })->get();
        }
    }

    static function BusinessPartnerDropdown(){

        $user=auth()->user();
        $company=auth()->user()->active_company() ?? null;

        if(auth()->user()->actor_id == 2){
            // return Partner::where('actor_id',4)
            return Partner::whereIn('actor_id',[4,6])->where('company_id', $company)
            ->get();
        }
        else{
            return Partner::when(auth()->user()->partner->actor_id == 4, function($query){
                $query
                // ->where('partner_type', 'business')
                ->where('id',auth()->user()->partner_id);
            })->get();
        }


    }
    static function BusinessPartnerDropdownIncludeDriver(){
        if(auth()->user()->actor_id == 2){
            return Partner::whereIn('actor_id',[4,5,6,8])->get();
        }
        else{
            return Partner::when(auth()->user()->partner->actor_id == 4, function($query){
                $query
                // ->where('partner_type', 'business')
                ->where('id',auth()->user()->partner_id);
            })->get();
        }


    }

    static function BusinessPartnerDropdownSimple(){
        // if(auth()->user()->actor_id == 2){
            return Partner::whereIn('actor_id',[4,5,6,8])->get();
        // }
        // else{
        //     return Partner::when(auth()->user()->partner->actor_id == 4, function($query){
        //         $query
        //         // ->where('partner_type', 'business')
        //         ->where('id',auth()->user()->partner_id);
        //     })->get();
        // }


    }

    static function CustomerPartnerDropdown(){
        return Partner::where('actor_id',6)->get();
    }

    static function DriverDropdown(){
        return Partner::where('actor_id',5)->get();
    }
    static function CustomerCountry(){
        return Partner::where('actor_id', 6)
        ->select('country') // Assuming 'country' is the name of the column that holds the country information
        ->distinct()
        ->get();
    }
    public static function unapprovedAgentsCount(){
        // dd(self::where('actor_id',4)->where('permission_status',null)->doesntHave('users')->get());
        return self::where('actor_id',4)->where('permission_status',null)->doesntHave('users')->count();

    }
    public static function vendorDropdown(){
        // if(auth()->user()->actor_id == 2){
            return Partner::where('company_id', auth()->user()->active_company())->whereIn('actor_id',[10])->get();
        // }
        // else{
        //     return Partner::when(auth()->user()->partner->actor_id == 4, function($query){
        //         $query
        //         // ->where('partner_type', 'business')
        //         ->where('id',auth()->user()->partner_id);
        //     })->get();
        // }


    }
}
