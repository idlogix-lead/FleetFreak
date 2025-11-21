<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ServiceProvider
 *
 * @property $id
 * @property $address
 * @property $city
 * @property $contact
 * @property $created_by
 * @property $updated_by
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ServiceProvider extends BaseModel
{
    use SoftDeletes;

    static $rules = [
		'address' => 'required',
		'city' => 'required',
		'contact' => 'required',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['address','city','contact'];
    protected $guarded = [];


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
