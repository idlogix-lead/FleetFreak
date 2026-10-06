<?php

namespace App\Models;

use App\Models\Concerns\ChecksGlobalPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property $id
 * @property $type
 * @property $name
 * @property $email
 * @property $role_id
 * @property $email_verified_at
 * @property $password
 * @property $remember_token
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 * @property $CNIC
 * @property $phone_no
 * @property $description
 * @property $image
 * @property $theme
 * @property $sidebar_color
 * @property $header_color
 * @property $actor_id
 *
 * @property Actor $actor
 * @property User $user
 * @property Role $role
 * @property User $user




 * @property Role[] $roles
 * @property Role[] $roles
 * @property RoleModule[] $roleModules
 * @property RoleModule[] $roleModules
 * @property ServiceProvider[] $serviceProviders
 * @property User[] $users
 * @property User[] $users
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class User extends AuthenticatableModel
{
    use HasFactory, Notifiable,HasApiTokens, ChecksGlobalPermission;

    // static $rules = [
	// 		'name' => 'required|string',
	// 		'email' => 'required|string',
	// 		'description' => 'string',
	// 		'image' => 'string',
	// 		'theme' => 'required',
	// 		'sidebar_color' => 'string',
	// 		'header_color' => 'string',
    // ];

    protected $perPage = 20;


    // protected $fillable = ['type', 'name', 'email', 'role_id', 'CNIC', 'phone_no', 'description', 'image', 'theme', 'sidebar_color', 'header_color', 'actor_id'];
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function get_user_roles_permissions(){
        $user = auth()->user();
        $role_id = $user->role_id;
        $permissions = RolePermission::where('role_id',$role_id)

        // ->with(['role_permission_type.rolePermissionTypeFunctions'])
        ->with(['role_module_functions', 'role_permission_type.sidebar.group', 'roleModule'])
        ->whereHas('roleModule.role_has_modules', function($query) use($role_id){
            return $query->where('role_id', $role_id);
        })
        ->get();
        // dd($permissions->toArray());
        return $permissions;
    }
    public function get_user_role_session_permissions(){
        return $this->get_user_roles_permissions();
    }
    public function role_module_permission_via_method($module_id, $target_method){
        $permissions = $this->get_user_role_session_permissions()->where('role_module_id',$module_id);
        // $permissions =  $permissions->where('role_module_id',$module_id);
        // dd($permissions);
        $permission = $permissions->filter(function($value, $key) use($target_method){
            $module =  $value->role_module_functions->filter(function($value, $key)  use($target_method){
                return $value->method == $target_method;
            });
            return $module->first();
        })->first();
        return $permission;
    }
    public function role_module_permission_via_action($module_id, $action){
        $permissions = $this->get_user_role_session_permissions()->where('role_module_id',$module_id);
        return  $permissions->filter(function($value, $key) use($action){
            return $value->role_permission_type->action == $action;
        })->first();
    }

    public function Customer(){
        return $this->hasOne(Customer::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }

    public function serviceProvider(){
        return $this->belongsTo(ServiceProvider::class);
    }
    public function socialprofile(){
        return $this->hasOne(UserSocialProfile::class);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'user_companies', 'user_id', 'company_id');
    }
    public static function active_company(){
        return auth()->user()->active_company_id;
    }
    public static function active_company_details(){
        // return $this->belongsTo(Company::class); // Adjust Company::class to your actual Company model namespace
        // return auth()->user()->active_company_id;
        return Company::where('id', auth()->user()->active_company_id)->first();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actor()
    {
        return $this->belongsTo(\App\Models\Actor::class, 'actor_id', 'id');
    }
    // public function companies(): BelongsToMany
    // {
    //     return $this->belongsToMany(Company::class); // Adjust Company::class to your actual Company model namespace
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id', 'id');
    }



    /**
     * The users $actor may manage (docs/HANDOVER.md §9.12). `users` is outside the organization scope, so every
     * user-management action loads its target through this. A super admin may manage anyone but other super admins.
     * Anyone else: users of their own client who belong to their active organization and to no organization the actor
     * isn't in, and who are neither a super admin nor the client's owner.
     */
    public function scopeManageableBy($query, User $actor)
    {
        $query->where('users.is_super_admin', 0);
        if ($actor->is_super_admin) {
            return $query;
        }
        if (! $actor->client_id || ! $actor->active_company_id) {
            return $query->whereRaw('1 = 0');
        }

        $actorCompanies = $actor->companies()->pluck('companies.id')->all();
        $ownerId = Client::whereKey($actor->client_id)->value('user_id');

        return $query->where('users.client_id', $actor->client_id)
            ->when($ownerId, fn ($q) => $q->whereKeyNot($ownerId))
            ->whereHas('companies', fn ($q) => $q->where('companies.id', $actor->active_company_id))
            ->whereDoesntHave('companies', fn ($q) => $q->whereNotIn('companies.id', $actorCompanies));
    }

    /** Agents created with a temporary password must set their own before using the app (AfterAuthentication). */
    public function mustChangePassword(): bool
    {
        return (int) $this->actor_id === 4 && (int) $this->flag === 1;
    }

    // ------------------------------------------------------
    static function store_user($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $actor_id = Role::where('id',$data['role_id'])->value('actor_id');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_no1'=> $data['phone_no1'],
            'phone_no2'=> $data['phone_no2'],
            'description' => $data['description'],
            'image' => $data['image'],
            'password' => Hash::make($data['password']),
            'role_id'=> $data['role_id'],
            'client_id'=> $data['client_id']??null,
            'actor_id'=>$actor_id,
            'created_by'=>$created_by,
            'is_company_admin' => $data['is_company_admin']??0,
            'active_company_id' => auth()->user()->active_company_id ?? null,
        ]);

        if(isset($normal)){
            UserCompany::create([
                'user_id' => $user->id,
                'company_id' => auth()->user()->active_company(),
            ]);
            if($user->role->actor_id == 9 && count($vehicle_ids) > 0){
                // throw new \Exception("Only Vehicle Manager Can Have Vehicle Access To Manage Vehcles!", 1);
                // Session::flash('error', "Only Vehicle Manager Can Have Vehicle Access To Manage Vehcles!");
                foreach($vehicle_ids as $vehicle_id){
                    VehicleManager::create([
                        'vehicle_id' => $vehicle_id,
                        'user_id' => $user->id,
                    ]);
                }
            }
        }


        return $user;
    }
    static function update_user($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        // $copy_user = $user;
        $user_data = User::with(['role'])->find($user->id);
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_no1'=> $data['phone_no1'],
            'phone_no2'=> $data['phone_no2'],
            'description' => $data['description'],
            'image' => $data['image'],
            'role_id'=>$data['role_id']??$data['role_id_hidden'],
            'updated_by'=>$updated_by,
        ]);
        VehicleManager::whereNotIn('vehicle_id', $vehicle_ids)->where('user_id', $user->id)->delete();
        if($user_data->role->actor_id == 9 && count($vehicle_ids) > 0){
            foreach($vehicle_ids as $vehicle_id){
                VehicleManager::updateOrCreate([
                    'vehicle_id' => $vehicle_id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }

    public static function current_currency(){
        return Currency::where('country', 'Pakistan')->first();
    }

    /**
     * Get the user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'id', 'user_id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function user()
    // {
    //     return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function complaints()
    // {
    //     return $this->hasMany(\App\Models\Complaint::class, 'id', 'created_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function complaints()
    // {
    //     return $this->hasMany(\App\Models\Complaint::class, 'id', 'updated_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function engineers()
    // {
    //     return $this->hasMany(\App\Models\Engineer::class, 'id', 'created_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function engineers()
    // {
    //     return $this->hasMany(\App\Models\Engineer::class, 'id', 'updated_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function engineerComplaints()
    // {
    //     return $this->hasMany(\App\Models\EngineerComplaint::class, 'id', 'created_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function engineerComplaints()
    // {
    //     return $this->hasMany(\App\Models\EngineerComplaint::class, 'id', 'updated_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function engineerFeedbacks()
    // {
    //     return $this->hasMany(\App\Models\EngineerFeedback::class, 'id', 'created_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function engineerFeedbacks()
    // {
    //     return $this->hasMany(\App\Models\EngineerFeedback::class, 'id', 'updated_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function machines()
    // {
    //     return $this->hasMany(\App\Models\Machine::class, 'id', 'created_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function machines()
    // {
    //     return $this->hasMany(\App\Models\Machine::class, 'id', 'updated_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function roles()
    // {
    //     return $this->hasMany(\App\Models\Role::class, 'id', 'created_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function roles()
    // {
    //     return $this->hasMany(\App\Models\Role::class, 'id', 'updated_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function roleModules()
    // {
    //     return $this->hasMany(\App\Models\RoleModule::class, 'id', 'created_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function roleModules()
    // {
    //     return $this->hasMany(\App\Models\RoleModule::class, 'id', 'updated_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function serviceProviders()
    // {
    //     return $this->hasMany(\App\Models\ServiceProvider::class, 'id', 'user_id');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function users()
    // {
    //     return $this->hasMany(\App\Models\User::class, 'id', 'created_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function users()
    // {
    //     return $this->hasMany(\App\Models\User::class, 'id', 'updated_by');
    // }
    public function routeNotificationForFirebase()
    {
        return $this->firebase_token;
    }
    public function vehicleManagers()
    {
        return $this->hasMany(VehicleManager::class, 'user_id');
    }

    public static function vehicleManagerDropdown(){
        return self::query()
        ->whereHas('role', function($role){
            return $role->where('actor_id', 9);
        })
        ->get();
    }

}
