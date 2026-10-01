<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VehicleCompany
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property VehicleModel[] $vehicleModels
 * @property Vehicle[] $vehicles
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class VehicleCompany extends BaseModel
{

    static $rules = [
			'name' => 'required',
			'description' => 'nullable',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    use BelongsToOrganization;

    protected $fillable = [
        'name', 'description', 'company_id', 'created_by', 'updated_by',
        'created_at', 'updated_at',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function vehicleModel()
    {
        return $this->hasMany(\App\Models\VehicleModel::class, 'vehicle_company_id', 'id');
    }
    public static function all_companies(){
        // return self::get();
        $vehicle_company = auth()->user()->active_company();

        return self::whereIn('company_id', [$vehicle_company])->get();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);

    }


}
