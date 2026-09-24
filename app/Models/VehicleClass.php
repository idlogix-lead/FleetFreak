<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VehicleClass
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property Vehicle[] $vehicles
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class VehicleClass extends BaseModel
{

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
    use BelongsToOrganization;

    protected $fillable = [
        'name', 'description', 'seats_allow', 'bags_allow', 'company_id',
        'created_by', 'updated_by', 'created_at', 'updated_at',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function vehicles()
    // {
    //     return $this->hasMany(\App\Models\Vehicle::class, 'vehicle_class_id', 'id');
    // }

    public function vehicleModel()
    {
        return $this->hasMany(\App\Models\VehicleModel::class, 'vehicle_class_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);

    }
    public static function vehicle_class(){
        // 1 car id in vehicle class:
        // return self::get();
        $vehicle_class = auth()->user()->active_company();

        return self::whereIn('company_id', $vehicle_class)->get();
    }
    // public static function Assigned_vehicle_classes(){
    //     // 1 car id in vehicle class:
    //     return self::whereHas('vehicleModel')->get();
    // }

    public static function store_vehicle_class($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);

        $user=auth()->user();

        $company=auth()->user()->active_company() ?? null;
        // dd($company);

       $vehicleClass= VehicleClass::create([
            'name' => $vehicleClassData['name'],
            'description' => $vehicleClassData['description']??null,
            'seats_allow' => $vehicleClassData['seats_allow'],
            'bags_allow' => $vehicleClassData['bags_allow'],
            'created_by' => $vehicleClassData['created_by'],
            'company_id' => $company,

        ]);
        return $vehicleClass;

        // Event::createEvent(21, $order->id, $order->order_no, 'created', 'Order created Successfully');
        // Event::createEvent(21, $order->id, $order->order_no, 'status_changed', 'your order is in ' . $order_data['overall_status']);
    }
    public static function update_vehicle_class($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        $user=auth()->user();
        $company=auth()->user()->active_company() ?? null;
        // dd($payload);

        $vehicleClass->update([
            'name' => $vehicleClassData['name'],
            'description' => $vehicleClassData['description']??null,
            'seats_allow' => $vehicleClassData['seats_allow'],
            'bags_allow' => $vehicleClassData['bags_allow'],
            'updated_by' => $vehicleClassData['updated_by'],
            'company_id' => $company,
        ]);

        // Event::createEvent(21, $order->id, $order->order_no, 'created', 'Order created Successfully');
        // Event::createEvent(21, $order->id, $order->order_no, 'status_changed', 'your order is in ' . $order_data['overall_status']);
    }

}
