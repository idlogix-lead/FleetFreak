<?php

namespace App\Models;

use App\Models\Concerns\ChecksGlobalPermission;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Route
 *
 * @property $id
 * @property $name
 * @property $from
 * @property $to
 * @property $distance
 * @property $distance_unit
 * @property $created_at
 * @property $updated_at
 *
 * @property PackageDetail[] $packageDetail
 * @property RouteRate[] $routeRates
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Route extends BaseModel
{
    use BelongsToOrganization, ChecksGlobalPermission;

    // static $rules = [
	// 		'name' => 'required|string',
	// 		'from' => 'required|string',
	// 		'to' => 'required|string',
	// 		'distance' => 'required',
	// 		'distance_unit' => 'required',
    // ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'from_loc', 'to_loc', 'distance', 'distance_unit', 'is_flight',
        'company_id', 'created_by', 'updated_by', 'created_at', 'updated_at',
    ];

    public function fromLoc()
    {
        return $this->belongsTo(\App\Models\Location::class, 'from_loc', 'id');
    }
    public function toLoc()
    {
        return $this->belongsTo(\App\Models\Location::class, 'to_loc', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);

    }

    public function ratelist()
    {
        return $this->hasMany(\App\Models\RateList::class);
    }



    static function store_route($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        // dd($payload);

        // $user = auth()->user();

        $company = auth()->user()->active_company() ?? null;


        $route = Route::create([
            'name' => $data['name'],
            'from_loc' => $data['from'],
            'to_loc'=> $data['to'],
            'distance'=> $data['distance'],
            'distance_unit' => $data['distance_unit'],
            'is_flight' => $data['is_flight'],
            'company_id' => $company,

            //'created_by'=>$created_by,


        ]);
        Event::createEvent(34,$route->id,$route->name,'created','route is created',null,null,$company);

        // RouteRate::create([
        //     'route_id'=>$route->id,
        //     'rate_with_fuel'=>$data['rate_with_fuel'],
        //     //'rate_without_fuel'=>$data['rate_without_fuel'],
        // ]);
    }
    static function update_route($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        // $copy_user = $user;
        // $user = auth()->user();

        // $company = $user->companies->first()->id ?? null;
        $company = auth()->user()->active_company() ?? null;

        $route->update([
            'name' => $data['name'],
            'from_loc' => $data['from'],
            'to_loc'=> $data['to'],
            'distance'=> $data['distance'],
            'distance_unit' => $data['distance_unit'],
            'is_flight' => $data['is_flight'],
            'company_id' => $company,

            //'updated_by'=> $updated_by

        ]);
        // $route_rates = RouteRate::where('route_id',$route->id)->first();

        // $route_rates->update([
        // 'rate_with_fuel'=>$data['rate_with_fuel'],
        // //'rate_without_fuel'=>$data['rate_without_fuel'],
        // ]);

    }

}
