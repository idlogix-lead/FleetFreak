<?php

namespace App\Models;

use App\Models\Concerns\ChecksGlobalPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 *
 * @property $id
 * @property $name
 * @property $home
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property RolePermission[] $rolePermissions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Role extends BaseModel
{
    use SoftDeletes, ChecksGlobalPermission;

    static $rules = [
		'name' => 'required',
		'home' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name','home'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rolePermissions()
    {
        return $this->hasMany('App\Models\RolePermission', 'role_id', 'id');
    }



    public function created_by_details()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function updated_by_details()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }



    /**
     * The roles $actor may give a user (docs/HANDOVER.md §9.12): their own client's roles, never the super admin role
     * (actor 1). Custom roles have no actor, so a null actor_id counts as allowed.
     */
    public function scopeAssignableBy($query, User $actor)
    {
        if (! $actor->is_super_admin) {
            $actor->client_id ? $query->where('roles.client_id', $actor->client_id) : $query->whereRaw('1 = 0');
        }

        return $query->where(fn ($q) => $q->whereNull('roles.actor_id')->orWhere('roles.actor_id', '!=', 1));
    }

    static function register_company_role($actor_id, $client_id){
        // $modules = RoleModule::
        // whereHas('role_module_actors', function($role_module_actors) use($actor_id){
        //     return $role_module_actors->where('actor_id', $actor_id);
        // })
        // whereHas('role_module_actors', function($role_module_actors) use($actor_id){
        //     return $role_module_actors->where('actor_id', $actor_id);
        // })
        // ->get()->pluck('id')->toArray();
        // Role::where('id', 2)

        //picking role modules from admin

        // $modules = RoleHasModule::where('role_id' , 2)->get()->pluck('role_module_id')->toArray();

        $role_blue_prints = [
            'admin' => [
                'actor_id' => 2,
            ],
            'agent' => [
                'actor_id' => 4,
            ],
            'driver' => [
                'actor_id' => 5,
            ],
            'management' => [
                'actor_id' => 7,
            ],
            'office_staff' => [
                'actor_id' => 7,
            ],
            'vehicle_manager' => [
                'actor_id' => 9,
            ],
        ];
        $admin_role_id = null;
        foreach($role_blue_prints as $role_name => $role_blue_print){
            $role_modules = RoleModuleActors::where('actor_id', $role_blue_print['actor_id'])->get()->pluck('role_module_id')->toArray();
            $role = Role::create([
                'name' => $role_name,
                'actor_id' => $role_blue_print['actor_id'],
                'client_id' => $client_id,
                'is_system' => 1,
                'home' => '/',
            ]);
            if($role_name == 'admin'){
                $admin_role_id = $role;
            }
            foreach($role_modules as $module){
                RoleHasModule::updateOrCreate([
                    'role_id' => $role->id,
                    'role_module_id' => $module,
                ]);
            }
            $role_module_permission_types = RolePermissionType::whereIn('role_module_id', $role_modules)
            ->select(['role_module_id', 'id as role_permission_type_id'])->get();
            foreach($role_module_permission_types as $role_module){
                $rolePermission = RolePermission::updateOrCreate([
                    'role_id' => $role->id,
                    'role_module_id' => $role_module->role_module_id,
                    'role_permission_type_id' => $role_module->role_permission_type_id,
                ],[
                    'permission' => 1,
                ]);
            }
        }

        return $admin_role_id;
    }

    public static function dropdown($client_id, $is_system=null){
        return Role::where('client_id', $client_id)
        ->when($is_system != null, function($query) use($is_system){
            return $query->where('is_system', $is_system);
        })
        ->get();
    }
}
