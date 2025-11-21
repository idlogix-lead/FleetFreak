<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RolePermissionType
 *
 * @property $id
 * @property $role_module_id
 * @property $action
 * @property $return
 * @property $denial_msg
 * @property $created_at
 * @property $updated_at
 *
 * @property RoleModule $roleModule
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class RolePermissionType extends BaseModel
{

    // static $rules = [
	// 		'role_module_id' => 'required',
	// 		'action' => 'required|string',
	// 		'function' => 'required',
	// 		'return' => 'required',
	// 		'denial_msg' => 'required|string',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['role_module_id', 'action', 'return', 'denial_msg'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roleModule()
    {
        return $this->belongsTo(\App\Models\RoleModule::class);
    }

    // public function rolePermissionTypeFunctions(){
    //     return $this->hasMany(RolePermissionTypeFunction::class);
    // }

    public function rolePermissionTypeFunctions(){
        return $this->hasMany(RolePermissionTypeFunction::class);
    }


    public function sidebar(){
        // return $this->hasOne(SidebarItems::class);// many possible
        return $this->hasMany(SidebarItems::class);// many possible
    }
    // public function group(){
    //     // return $this->hasOne(SidebarItems::class);// many possible
    //     return $this->hasMany(SidebarItemGroupLine::class, 'sidebar_id');// many possible
    // }
}
