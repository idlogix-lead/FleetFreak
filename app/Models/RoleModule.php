<?php

namespace App\Models;

use App\Models\Concerns\ChecksGlobalPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RoleModule
 *
 * @property $id
 * @property $name
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property RolePermission[] $rolePermissions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class RoleModule extends BaseModel
{
    use SoftDeletes, ChecksGlobalPermission;

    static $rules = [
		'name' => 'required',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rolePermissions()
    {
        return $this->hasMany('App\Models\RolePermission', 'role_module_id', 'id');
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
    // public function role_permission_type(){
    //     return $this->hasMany(RolePermissionType::class);
    // }

    public function role_permission_type(){
        return $this->hasMany(RolePermissionType::class);
    }
    public function role_module_actors(){
        return $this->hasMany(RoleModuleActors::class);
    }
    public function role_has_modules(){
        return $this->hasMany(RoleHasModule::class);
    }

    public static function getRoleModuleDropdown($ignore = []){
        // RoleHasModule::whereHas('role')
        $id = auth()->user()->client->user->role_id;
        return self::whereHas('role_has_modules',function($query) use($id){
            $query->where('role_id', $id);
        })->get();
    }


}
