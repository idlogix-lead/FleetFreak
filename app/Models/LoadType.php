<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LoadType
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $company_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Company $company
 * @property OrderDetail[] $orderDetails
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class LoadType extends BaseModel
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
    // protected $fillable = ['name', 'description', 'company_id'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderDetails()
    {
        return $this->hasMany(\App\Models\OrderDetail::class, 'id', 'type_of_load');
    }

    public static function load_type()
    {
        $company_id = auth()->user()->active_company();
        $load_types = self::where('company_id', $company_id)->get();
        // dd($load_types);
        return $load_types;
    }
}
