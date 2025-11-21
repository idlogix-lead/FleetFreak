<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



/**
 * Class Driver
 *
 * @property $id
 * @property $name
 * @property $vehicle_id
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @property User $user
 * @property Vehicle $vehicle
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Driver extends BaseModel
{

    // static $rules = [
	// 		'name' => 'required|string',
	// 		'vehicle_id' => 'required',
    // ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'vehicle_id'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function user()
    // {
    //     return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class, 'vehicle_id', 'id');
    }

    static function store_driver($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $driver = Driver::create([
            'name'=>$driver_data['name'],
            'vehicle_id'=>$driver_data['vehicle_id'],
            'created_by'=>$driver_data['created_by'],
        ]);
    }

    static function update_driver($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $driver->update([
            'name'=>$driver_data['name'],
            'vehicle_id'=>$driver_data['vehicle_id'],
            'updated_by'=>$driver_data['updated_by'],
        ]);
    }
    static function DriverDropdown(){
        $user=auth()->user();
        $company=$user->companies->first();
        return Partner::where('actor_id',5)
        ->when($company, function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })
        ->get();
    }
    public static function getAvailableDrivers($order)
{
    // Get the start and end times for the order
        $start = Carbon::parse($order->pickup_time);

        $estimatedHours = (int) $order->rate_list->estimated_time; // assuming estimated_time is in hours

        // dd($estimatedHours);
        $end = $start->copy()->addHours($estimatedHours);
        // dd($end);

        // Find busy vehicles
        $busy_drivers = OrderDetail::where('date', $order->date)
            ->whereNotNull('vehicle_id')

            ->where(function($query) use ($start, $end) {
                // Check if existing order's pickup time overlaps with the new order's start and end times
                // dd($start,$end);
                $query->whereTime('pickup_time', '<=', $start->toTimeString())
                    ->orWhereTime('pickup_time', '<=', $end->toTimeString());
                    // dd($query);
            })
            ->join('rate_lists','order_lines.rate_list_id', '=', 'rate_lists.id')

            ->where(function ($query) use ($start, $end) {
                // $query->join('rate_lists','order_lines.rate_list_id', '=', 'rate_lists.id');

                $query->whereTime(DB::raw("ADDTIME('{$start->toTimeString()}', SEC_TO_TIME(rate_lists.estimated_time * 60))"), '>=', $end->toTimeString())
                      ->orWhereTime(DB::raw("ADDTIME('{$start->toTimeString()}', SEC_TO_TIME(rate_lists.estimated_time * 60))"), '>=', $start->toTimeString());
                    //   dd($query);
            })
            ->get()
            // dd($busy_vehicles);
            ->pluck('driver_id')
            // dd($busy_vehicles);

            ->toArray();
            // dd(Vehicle::whereNotIn('id', $busy_vehicles)->get());

        // Return available vehicles
        return Vehicle::whereNotIn('id', $busy_drivers)->get();
}

}
