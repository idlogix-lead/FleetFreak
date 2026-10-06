<?php

namespace App\Models;

use App\Models\Concerns\ChecksGlobalPermission;
use App\Models\Concerns\BelongsToOrganization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vehicle
 *
 * @property $id
 * @property $vehicle_identification_number
 * @property $vehicle_company_id
 * @property $model
 * @property $year
 * @property $color
 * @property $license_plate_number
 * @property $registration
 * @property $ownership
 * @property $fuel_type
 * @property $engine_type
 * @property $transmission_type
 * @property $vehicle_class_id
 * @property $weight
 * @property $created_at
 * @property $updated_at
 *
 * @property VehicleCompany $company
 * @property VehicleClass $vehicleClass
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Vehicle extends BaseModel
{
    use BelongsToOrganization, ChecksGlobalPermission;

    // static $rules = [
    //         'vehicle_identification_number' => 'required|alpha_num',
    //         'model' => 'required|string',
    //         'year' => 'required',
    //         'color' => 'string',
    //         'license_plate_number' => 'required|string',
    //         'registration' => 'required|string',
    //         'ownership' => 'required',
    //         'fuel_type' => 'required',
    //         'engine_type' => 'string',
    //         'transmission_type' => 'required',
    // ];

    protected $perPage = 20;
    protected $casts = [
        'reason' => 'string',
    ];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vehicle_identification_number', 'driver_id', 'vehicle_company_id',
        'model', 'year', 'color', 'vehicle_no', 'registration_no',
        'ownership', 'is_ac', 'is_status', 'fuel_type', 'engine_type', 'transmission_type',
        'company_id', 'vehicle_class_id', 'vehicle_model_id',
        'weight', 'milage', 'car_condition', 'image', 'reason',
        'created_by', 'updated_by',
        'maintenance_interval_days', 'maintenance_oilchange_interval_km',
        'chassis_no', 'route_permits_no', 'route_permits_expiry_date',
        'fitness_certificate_no', 'insurance_no', 'insurance_provider',
        'insurance_start_date', 'insurance_provider_contact_no', 'prefix_insurance_provider_contact_no',
        'created_at', 'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function car_company()
    {
        return $this->belongsTo(\App\Models\VehicleCompany::class, 'vehicle_company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicleClass()
    {
        return $this->belongsTo(\App\Models\VehicleClass::class, 'vehicle_class_id', 'id');
    }
    public function vehicleModel()
    {
        return $this->belongsTo(\App\Models\VehicleModel::class, 'vehicle_model_id', 'id');
    }
    public function driver()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'driver_id', 'id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function comapny()
    {
        return $this->belongsTo(Company::class);

    }
    public static function availableVehicles()
    {

        return Vehicle::whereDoesntHave('orderDetails')->count();
    }


    // public function orderDetails()
    // {
    //     return $this->hasMany(\App\Models\OrderDetail::class, 'vehicle_id');
    // }
    // public function orderdetails()
    // {
    //     return $this->hasMany(OrderDetail::class);
    // }

    public static function store_vehicle($payload)
    {
        // Extract variables from the payload
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($data['model']);

        // Ensure the $data array has the default image if not set
        if (!isset($data['image'])) {
            $data['image'] = null;
        }
        $user = auth()->user();

        $company = auth()->user()->active_company() ?? null;
        $vehicle_manager_id = $data['vehicle_manager_id'];
        unset($data['vehicle_manager_id']);
        // Create the vehicle record
        $vehicle = Vehicle::create([
            'vehicle_identification_number' => $data['vehicle_identification_number'],
            'chassis_no' => $data['chassis_no'],
            'route_permits_no' => $data['route_permits_no'],
            'route_permits_expiry_date' => $data['route_permits_expiry_date'],
            'fitness_certificate_no' => $data['fitness_certificate_no'],
            'insurance_no' => $data['insurance_no'],
            'insurance_provider' => $data['insurance_provider'],
            'prefix_insurance_provider_contact_no' => $data['prefix_insurance_provider_contact_no'],
            'insurance_provider_contact_no' => $data['insurance_provider_contact_no'],
            'insurance_start_date' => $data['insurance_start_date'],
            'vehicle_model_id' => $data['model'],
            // 'vehicle_company_id' => $data['vehicle_company_id'],
            'driver_id' => $data['driver_id'] ?? null,
            'year' => $data['year']?? 0,
            'color' => $data['color'],
            'vehicle_no' => $data['vehicle_no'],
            'registration_no' => $data['registration_no'],
            'ownership' => $data['ownership'],
            'fuel_type' => $data['fuel_type'],
            'engine_type' => $data['engine_type'],
            // 'vehicle_class_id' => $data['vehicle_class_id'],
            'transmission_type' => $data['transmission_type'],
            'weight' => $data['weight'],
            'milage' => $data['milage'],
            'maintenance_interval_days'=>$data['maintenance_interval_days'],
            'maintenance_oilchange_interval_km'=>$data['maintenance_oilchange_interval_km'],
            'image' => $data['image'],
            'car_condition' => $data['car_condition']?? null,
            'is_ac' => $data['is_ac'],
            'is_status' => $data['is_status'],
            'reason' => null,
            'created_by' => $created_by,
            'company_id' => $company,
        ]);
        VehicleManager::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => $vehicle_manager_id,
        ]);

        // Handle image upload if an image is provided and is a file object
        if (isset($data['image']) && is_object($data['image'])) {
            $image = $data['image'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/resources_images/uploads'), $imageName);
            $image_url = 'resources_images/uploads/' . $imageName;

            // Update the image URL in the vehicle record
            Vehicle::where('id', $vehicle->id)->update(['image' => $image_url]);
        }
    }

    public static function update_vehicle($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        if (!isset($data['image'])) {
            $data['image'] = null;
        }
        $user = auth()->user();
        $company = auth()->user()->active_company() ?? null;
        VehicleManager::updateOrCreate(
            [
                'vehicle_id' => $vehicle->id,
            ],
            [
                'user_id' => $data['vehicle_manager_id']
            ]
        );
        unset($data['vehicle_manager_id']);
        $vehicle->update([
            'vehicle_identification_number' => $data['vehicle_identification_number'],
            'chassis_no' => $data['chassis_no'],
            'route_permits_no' => $data['route_permits_no'],
            'route_permits_expiry_date' => $data['route_permits_expiry_date'],
            'fitness_certificate_no' => $data['fitness_certificate_no'],
            'insurance_no' => $data['insurance_no'],
            'insurance_provider' => $data['insurance_provider'],
            'prefix_insurance_provider_contact_no' => $data['prefix_insurance_provider_contact_no'],
            'insurance_provider_contact_no' => $data['insurance_provider_contact_no'],
            'insurance_start_date' => $data['insurance_start_date'],
            'vehicle_model_id' => $data['model'],
            // 'vehicle_company_id'=> $data['vehicle_company_id'],
            'driver_id' => $data['driver_id'] ?? null,
            'year' => $data['year'],
            'color' => $data['color'],
            'vehicle_no' => $data['vehicle_no'],
            'registration_no' => $data['registration_no'],
            'ownership' => $data['ownership'],
            'fuel_type' => $data['fuel_type'],
            'engine_type' => $data['engine_type'],
            // 'vehicle_class_id'=> $data['vehicle_class_id'],
            'transmission_type' => $data['transmission_type'],
            'weight' => $data['weight'],
            'milage' => $data['milage'],
            'maintenance_interval_days' => $data['maintenance_interval_days'],
            'maintenance_oilchange_interval_km' => $data['maintenance_oilchange_interval_km'],
            'image' => $data['image'],
            'car_condition' => $data['car_condition'],
            'is_ac' => $data['is_ac'],
            'is_status' => $data['is_status'],
            'updated_by' => $updated_by,
            'reason' => null,
            'company_id' => $company,
        ]);
        if (isset($data['image']) && is_object($data['image'])) {
            $image = $data['image'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/resources_images/uploads'), $imageName);
            $image_url = 'resources_images/uploads/' . $imageName;

            // Update the image URL in the vehicle record
            Vehicle::where('id', $vehicle->id)->update(['image' => $image_url]);
        }

    }
    public static function vehicles()
    {
        return Vehicle::get();
    }
    //  public static function getVehicles()
    // {
    //     return Vehicle::where('company_id',);
    // }
    public static function VehicleDropdown()
    {
        $user = auth()->user();
        $companyId = $user->active_company();
        return Vehicle::with([
            'vehicleModel',
            'car_company',
            'vehicleClass'
        ])
        ->where('company_id', $companyId)
        ->get();
    }

    public static function VehicleManagerDropdown($user_id = null)
    {
        $user = auth()->user();
        $companyId = $user->active_company();

        return Vehicle::with([
            'vehicleModel',
            'car_company',
            'vehicleClass'
        ])
        ->where('company_id', $companyId)

        // ->whereDoesntHave('vehicleManager')
        ->whereDoesntHave('vehicleManager', function ($query) use ($user_id) {
            if (!is_null($user_id)) {
                return $query->where('user_id', '!=', $user_id);
            }
        })
        ->get();
    }

    // static function VehicleDropdown($vehicle_class_id){
    //     dd($vehicle_class_id);
    // return Vehicle::whereHas('vehicleModel',function($query) use ($vehicle_class_id){
    //     $query->whereHas('vehicleClass',function($q) use ($vehicle_class_id) {
    //         $q->where('id',$vehicle_class_id);
    //     });
    // })->get();
    // }
    // static function VehicleDropdownOrder(){
    //     return self::with('vehicleClass', 'vehicleModel')
    //             ->distinct('vehicle_model_id')
    //             ->get();
    // }
    public static function VehicleDropdownOrder()
    {
        // $user = auth()->user();
        $companyId = auth()->user()->active_company();
        return self::whereHas('vehicleModel', function ($query) use ($companyId) {
            $query->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            });
        })
        ->get();
    }

//     public static function getAvailableVehicles($order)
//     {
//         // Get the start and end times for the order
//         $start = Carbon::parse($order->pickup_time);

//         $estimatedHours = (int) $order->rate_list->estimated_time; // assuming estimated_time is in hours

//         // dd($estimatedHours);
//         $end = $start->copy()->addMinutes($estimatedHours);
//         // dd($end);

//         // Find busy vehicles
//         $busy_vehicles = OrderDetail::where('date', $order->date)
//             ->whereNotNull('vehicle_id')

//             ->where(function($query) use ($start, $end) {
//                 // Check if existing order's pickup time overlaps with the new order's start and end times
//                 // dd($start,$end);
//                 $query->whereTime('pickup_time', '<=', $start->toTimeString())
//                     ->orWhereTime('pickup_time', '<=', $end->toTimeString());
//                     // dd($query);
//             })
//             ->join('rate_lists','order_details.rate_list_id', '=', 'rate_lists.id')

//             ->where(function ($query) use ($start, $end) {
//                 // $query->join('rate_lists','order_details.rate_list_id', '=', 'rate_lists.id');

//                 $query->whereTime(DB::raw("ADDTIME('{$start->toTimeString()}', SEC_TO_TIME(rate_lists.estimated_time * 60))"), '>=', $end->toTimeString())
//                       ->orWhereTime(DB::raw("ADDTIME('{$start->toTimeString()}', SEC_TO_TIME(rate_lists.estimated_time * 60))"), '>=', $start->toTimeString());
//                     //   dd($query);
//             })
//             ->get()
//             // dd($busy_vehicles);
//             ->pluck('vehicle_id')
//             // dd($busy_vehicles);

//             ->toArray();
//             // dd(Vehicle::whereNotIn('id', $busy_vehicles)->get());

//         // Return available vehicles
//         return Vehicle::whereNotIn('id', $busy_vehicles)->get();
// }

    public static function getAvailableVehicles($order)
    {
        // Get the start and end times for the order
        $start = Carbon::parse($order->pickup_time);
        // dd($order);
        // $estimatedMins = (int) $order->rate_list ? $order->rate_list->estimated_time : 0;
        $estimatedMins = $order->rate_list ? (int) $order->rate_list->estimated_time : 0;

        $end = $start->copy()->addMinutes($estimatedMins);
        // dd($end);
        // dd($estimatedMins);
        // Find busy vehicles
        $busy_vehicles = OrderDetail::where('date', $order->date)->where('status', 'incomplete')
            ->whereNotNull('vehicle_id')
            ->where(function ($query) use ($start, $end) {
                // Check if existing order's time overlaps with the new order's start and end times
                $query->where(function ($query) use ($start, $end) {
                    // mysql query
                //     $query->whereTime('pickup_time', '<=', $end->toTimeString())
                //         ->whereRaw("ADDTIME(pickup_time, SEC_TO_TIME(rate_lists.estimated_time * 60 * 60)) >= '{$start->toTimeString()}'");
                // });
                // postgres query
                $query->whereTime('pickup_time', '<=', $end->toTimeString())
                ->whereRaw("
                pickup_time + INTERVAL '1 second' * (CAST(rate_lists.estimated_time AS integer) * 60) >= ?
            ", [$start->toTimeString()]);
        });
            })
            ->join('rate_lists', 'order_lines.rate_list_id', '=', 'rate_lists.id')
            ->get()
            ->pluck('vehicle_id')
            ->toArray();

        // Return available vehicles

        $model_id = $order->order->vehicle_model_id;
        $class_id = $order->order->vehicle_class_id;

        // test code:
        // Fetch vehicles by model
        $model_vehicles = Vehicle::whereNotIn('id', $busy_vehicles)
            ->where('is_status', 'active')
            ->whereHas('vehicleModel', function ($query) use ($model_id) {
                $query->where('id', $model_id);
            })->get();
        // $model_vehicles = collect();

        // Check if any model vehicles are available
        $is_model_available = !$model_vehicles->isEmpty();

        // If no model vehicles, fetch class-based vehicles
        $class_vehicles = Vehicle::whereNotIn('id', $busy_vehicles)
            ->where('is_status', 'active')
            ->whereHas('vehicleModel', function ($query) use ($class_id) {
                $query->whereHas('vehicleClass', function ($q) use ($class_id) {
                    $q->where('id', $class_id);
                });
            })->get();

        // Return both sets of vehicles and the flag
        return [
            'model_vehicles' => $model_vehicles,
            'class_vehicles' => $class_vehicles,
            'is_model_available' => $is_model_available,
        ];

        // -------------------------------------------
        // original code:
        // return Vehicle::whereNotIn('id', $busy_vehicles)->where('is_status','active')->whereHas('vehicleModel',function($query) use ($model_id){
        //     $query->where('id',$model_id);
        // })->get() ?? Vehicle::whereNotIn('id', $busy_vehicles)->where('is_status','active')->whereHas('vehicleModel',function($query) use ($class_id){
        //     $query->whereHas('vehicleClass',function($q) use ($class_id) {
        //                 $q->where('id',$class_id);
        //             });
        // })->get();
        // ---------------------------------------------
        // $vehicle_class_id = $order->order->vehicle_class_id;
    }

    public static function countByStatus()
    {
        $company_id = auth()->user()->active_company();
        // dd($company_id);
        return [
            'active' => self::where('company_id', $company_id)->where('is_status', 'active')->count(),
            'inactive' => self::where('company_id', $company_id)->where('is_status', 'inactive')->count(),
            'sold' => self::where('company_id', $company_id)->where('is_status', 'sold')->count(),
        ];
    }
    public function vehicleManager()
    {
        return $this->hasOne(VehicleManager::class, 'vehicle_id', 'id');
    }
}
