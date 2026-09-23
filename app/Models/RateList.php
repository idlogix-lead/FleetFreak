<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Package
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property PackageDetail[] $packageDetail
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class RateList extends BaseModel
{

    // static $rules = [
	// 		'name' => 'required|string',
	// 		'description' => 'string',
    // ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    use BelongsToOrganization;

    protected $fillable = [
        'name', 'description', 'price', 'route_id', 'vehicle_class_id',
        'estimated_time', 'company_id', 'created_by', 'updated_by',
        'created_at', 'updated_at',
    ];



    public function route()
    {
        return $this->belongsTo(\App\Models\Route::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);

    }
    public function vehicle_class()
    {
        return $this->belongsTo(\App\Models\VehicleClass::class);
    }
    public function scopecheckGlobal($query, $role_module_id){
        return $query->when(!auth()->user()->role_module_permission_via_action($role_module_id,'global')->permission, function($query){
            $query->where('created_by', auth()->user()->id);
        });
        // return $this;
    }

    static function store_ratelist($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $user=auth()->user();
        $company=auth()->user()->active_company() ?? null;

        $ratelist = RateList::create([
            'name' => $ratelist['name'],
            'description' => $ratelist['description'],
            'price'=>$ratelist['price'],
            'route_id'=>$ratelist['route_id'],
            'vehicle_class_id'=>$ratelist['vehicle_class_id'],
            'estimated_time'=>$ratelist['estimated_time'],
            'company_id' => $company,
            //'created_by'=>$created_by,
        ]);
        Event::createEvent(27,$ratelist->id,$ratelist->name,'created','ratelist is created',null,null,$company);



    }
    static function update_ratelist($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        // $copy_user = $user;

        $company = auth()->user()->active_company() ?? null;

        RateList::where('id',$ratelist_id)->update([
            'name' => $ratelist['name'],
            'description' => $ratelist['description'],
            'price'=>$ratelist['price'],
            'route_id'=>$ratelist['route_id'],
            'vehicle_class_id'=>$ratelist['vehicle_class_id'],
            'estimated_time'=>$ratelist['estimated_time'],
            'company_id' =>$company,

            //'updated_by'=> $updated_by
        ]);
        //PackageDetail::where('package_id',$package->id)->delete();

        // foreach ($data['route_id'] as $key=>$routeId) {
        //     PackageDetail::create([
        //         'package_id' => $package->id,
        //         'route_id' => $routeId,
        //     ]);
        // }

    }

    static function RouteRateDropDown(){
        return RateList::get();
    }
    static function RouteRateDropDownunique()
    {
        $ratelist = auth()->user()->active_company();

        return self::with('route')->where('company_id', $ratelist)->get()
            ->unique(function ($item) {
            return $item->route->fromLoc->name;
        });
        // return self::with('route')
        //     ->get()
        //     ->unique(function ($item) {
        //         return $item->route->fromLoc->name;
        //     });
    }
    static function RouteRateDropDownuniqueto()
    {
        $ratelist = auth()->user()->active_company();
        return self::with('route')->where('company_id', $ratelist)->get()
            ->unique(function ($item) {
            return $item->route->toLoc->name;
        });

        // return self::with('route')
        //     ->get()
        //     ->unique(function ($item) {
        //         return $item->route->toLoc->name;
        //     });
    }
}
