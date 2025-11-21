<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Customer
 *
 * @property $id
 * @property $name
 * @property $email
 * @property $address
 * @property $city
 * @property $contact
 * @property $created_by
 * @property $updated_by
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Machine[] $machines
 * @property User $user
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Customer extends BaseModel
{
    use SoftDeletes;

    // static $rules = [
	// 	'name' => 'required',
	// 	'email' => 'required',
	// 	'address' => 'required',
	// 	'city' => 'required',
	// 	'contact' => 'required',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name','email','address','city','contact'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints()
    {
        return $this->hasMany('App\Models\Complaint', 'customer_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function machines()
    {
        return $this->hasMany('App\Models\Machine', 'customer_id', 'id');
    }

    // public function users(){
    //     return $this->belongsTo(User::class);
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function created_by_details()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function updated_by_details()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

    public function user(){
        return $this->hasOne(User::class);
    }


}
