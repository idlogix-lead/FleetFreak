<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RolePermission
 *
 * @property $id
 * @property $role_module_id
 * @property $role_id
 * @property $create
 * @property $read
 * @property $update
 * @property $delete
 * @property $recover
 * @property $global
 * @property $created_at
 * @property $updated_at
 *
 * @property Role $role
 * @property RoleModule $roleModule
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class RolePermission extends BaseModel
{

    static $rules = [
		'create' => 'required',
		'read' => 'required',
		'update' => 'required',
		'delete' => 'required',
		'recover' => 'required',
		'global' => 'required',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['role_module_id','role_id','create','read','update','delete','recover','global'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function roleModule()
    {
        return $this->hasOne('App\Models\RoleModule', 'id', 'role_module_id');
    }

    // BelongsToMan
    public function role_permission_type(){
        // return $this->hasOne(RolePermissionType::class,'id','role_permission_type_id');
        return $this->belongsTo(RolePermissionType::class)
        // ->leftJoin('role_permission_type_functions','role_permission_type_functions.role_permission_type_id','role_permission_types.id')
        // ->select('role_permission_types.*','role_permission_type_functions.method')
        ;
    }

    public function role_module_functions(){
        // return $this->hasOne(RolePermissionType::class,'id','role_permission_type_id');
        return $this->hasMany(RolePermissionTypeFunction::class,'role_permission_type_id','role_permission_type_id')
        // ->leftJoin('role_permission_type_functions','role_permission_type_functions.role_permission_type_id','role_permission_types.id')
        // ->select('role_permission_types.*','role_permission_type_functions.method')
        ;
    }
    // public function created_by_details()
    // {
    //     return $this->hasOne('App\Models\User', 'id', 'created_by');
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasOne
    //  */
    // public function updated_by_details()
    // {
    //     return $this->hasOne('App\Models\User', 'id', 'updated_by');
    // }


}
