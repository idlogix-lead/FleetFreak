<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Location extends BaseModel
{

    // static $rules = [
	// 		'name' => 'string',
	// 		'description' => 'string',
    // ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'description'];
    protected $guarded = [];

    public function routesFrom()
    {
        return $this->hasMany(\App\Models\Route::class, 'from_loc', 'id');
    }
    public function routesTo()
    {
        return $this->hasMany(\App\Models\Route::class, 'to_loc', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);

    }

    public static function all_locations(){
        // return self::all();
        $companyIds = auth()->user()->active_company();

        return self::whereIn('company_id', [$companyIds])->get();
    }

    public static function store_location($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        $user = auth()->user();

        $company = auth()->user()->active_company() ?? null;

        Location::create([
            'name'=>$location_data['name'],
            'description'=>$location_data['description'],
            'company_id' => $company,
    ]);

    }

    public static function update_location($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }

        $user=auth()->user();
        $company=auth()->user()->active_company() ?? null;

        $location->update([
            'name'=>$location_data['name'],
            'description'=>$location_data['description'],
            'company_id' => $company,

         ]);

    }
}
