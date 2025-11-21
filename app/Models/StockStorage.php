<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StockStorage
 *
 * @property $id
 * @property $client_id
 * @property $company_id
 * @property $description
 * @property $is_active
 * @property $is_default
 * @property $product_id
 * @property $locator_id
 * @property $on_hand_qty
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property Client $client
 * @property Company $company
 * @property User $user
 * @property Locator $locator
 * @property Product $product
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class StockStorage extends Model
{
    
    // static $rules = [
    //         'product_id'=>'required',
    //         'locator_id'=>'required',
    //         'on_hand_qty'=>'nullable',
	// 		'description' => 'string',
	// 		'is_active' => 'nullable',
	// 		'is_default' => 'nullable',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['client_id', 'company_id', 'description', 'is_active', 'is_default', 'product_id', 'locator_id', 'on_hand_qty'];
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
    public function locator()
    {
        return $this->belongsTo(\App\Models\Locator::class, 'locator_id', 'id');
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

    static function store_manufacturing_company($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $stockStorage = StockStorage::create([
           
            'prodoct_id' => $data['prodoct_id'],
            'locator_id' => $data['locator_id'],
            'on_hand_qty' => $data['on_hand_qty'],
			'is_active' => $data['is_active'],
			'is_default' => $data['is_default'],
            'created_by'=>$data['created_by'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
        ]);

    }
    static function update_manufacturing_company($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $stockStorage->update([
            'prodoct_id' => $data['prodoct_id'],
            'locator_id' => $data['locator_id'],
            'on_hand_qty' => $data['on_hand_qty'],
			'is_active' => $data['is_active'],
			'is_default' => $data['is_default'],
            'updated_by'=> $data['updated_by']
        ]);

    }

}
