<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductPrice
 *
 * @property $id
 * @property $client_id
 * @property $company_id
 * @property $created_by
 * @property $updated_by
 * @property $product_id
 * @property $price_list_version_id
 * @property $list_price
 * @property $standard_price
 * @property $limit_price
 * @property $description
 * @property $is_active
 * @property $is_defalut
 * @property $created_at
 * @property $updated_at
 *
 * @property Client $client
 * @property Company $company
 * @property User $user
 * @property PriceListVersion $priceListVersion
 * @property Product $product
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProductPrice extends BaseModel
{
    
    static $rules = [
			'description' => 'string',
			'is_active' => 'required',
			'is_defalut' => 'required',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['client_id', 'company_id', 'product_id', 'price_list_version_id', 'list_price', 'standard_price', 'limit_price', 'description', 'is_active', 'is_defalut'];
    protected $guarded = [];

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id', 'id');
    }
    
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
    public function created_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priceListVersion()
    {
        return $this->belongsTo(\App\Models\PriceListVersion::class, 'price_list_version_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }
    

}
