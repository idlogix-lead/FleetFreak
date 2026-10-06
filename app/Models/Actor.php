<?php

namespace App\Models;

use App\Models\Concerns\ChecksGlobalPermission;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Actor
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property RoleModule[] $roleModules
 * //@property RoleModuleActor[] $roleModuleActors
 * @property User[] $users
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Actor extends BaseModel
{
    use ChecksGlobalPermission;

    // static $rules = [
	// 		'name' => 'required|string',
	// 		'description' => 'string',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'description'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roleModules()
    {
        return $this->hasMany(\App\Models\RoleModule::class, 'id', 'actor_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function roleModuleActors()
    // {
    //     return $this->hasMany(\App\Models\RoleModuleActor::class, 'id', 'actor_id');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'id', 'actor_id');
    }


}
