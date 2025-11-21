<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VehicleModel
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @property User $user
 * @property Vehicle[] $vehicles
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class VehicleModel extends BaseModel
{

    // static $rules = [
	// 		'name' => 'required|string',
	// 		'description' => 'required|string',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function create_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }
    public function vehicleClass()
    {
        return $this->belongsTo(\App\Models\VehicleClass::class, 'vehicle_class_id', 'id');
    }
    public function carCompany()
    {
        return $this->belongsTo(\App\Models\VehicleCompany::class, 'vehicle_company_id', 'id');
    }
    public function vehicles()
    {
        return $this->hasMany(\App\Models\Vehicle::class, 'vehicle_model_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function update_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function vehicles()
    // {
    //     return $this->hasMany(\App\Models\Vehicle::class, 'id', 'vehicle_model_id');
    // }
    static function VehicleModel(){
        // return VehicleModel::get();
        $vehicle_modal = auth()->user()->active_company();

        return self::whereIn('company_id', $vehicle_modal)->get();
    }

}
