<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DriverAssignment
 *
 * @property $id
 * @property $order_detail_id
 * @property $driver_id
 * @property $vehicle_id
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @property Partner $partner
 * @property OrderDetail $orderDetail
 * @property User $user
 * @property Vehicle $vehicle
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DriverAssignment extends BaseModel
{

    static $rules = [
			'order_detail_id' => 'required',
			'driver_id' => 'required',
			'vehicle_id' => 'required',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['order_detail_id', 'driver_id', 'vehicle_id'];
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
    public function partner()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'driver_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderDetail()
    {
        return $this->belongsTo(\App\Models\OrderDetail::class, 'order_detail_id', 'id');
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


}
