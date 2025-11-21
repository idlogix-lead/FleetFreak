<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AccountType
 *
 * @property $id
 * @property $name
 * @property $code
 * @property $parent_id
 * @property $company_id
 * @property $is_active
 * @property $description
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property Company $company
 * @property User $user
 * @property AccountType $accountType
 * @property User $user
 * @property AccountType[] $accountTypes
 * @property Account[] $accounts
 * @property Account[] $accounts
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AccountType extends BaseModel
{

    static $rules = [
			'name' => 'required|string',
			'code' => 'required|string',
			'is_active' => 'required',
			'description' => 'string',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'code', 'parent_id', 'company_id', 'is_active', 'description'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by_details()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountType()
    {
        return $this->belongsTo(\App\Models\AccountType::class, 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_by_details()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parent()
    {
        return $this->hasOne(\App\Models\AccountType::class, 'id', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountSubTypes()
    {
        return $this->hasMany(\App\Models\Account::class, 'id', 'account_subtype_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountTypes()
    {
        return $this->hasMany(\App\Models\Account::class, 'id', 'account_type_id');
    }

    // haris:
    public function parent_id()
    {
        return $this->belongsTo(\App\Models\AccountType::class, 'parent_id');
    }

    public function subTypes()
    {
        return $this->hasMany(\App\Models\AccountType::class, 'parent_id');
    }
    //end-------

    public static function dropdown($ignore = [], $parent = false, $parent_id = null){
        return self::when($parent, function($query){
            return $query->whereNull('parent_id');
        })->when(!$parent, function($query) use($parent_id){
            return $query->where('parent_id', $parent_id);
        })
        ->where('is_active', 1)
        ->whereNotIn('id', $ignore)->get();
    }
}
