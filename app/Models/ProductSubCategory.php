<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductSubCategory
 *
 * @property $id
 * @property $client_id
 * @property $company_id
 * @property $name
 * @property $description
 * @property $code
 * @property $product_category_id
 * @property $created_at
 * @property $updated_at
 * @property $created_by
 * @property $updated_by
 *
 * @property Client $client
 * @property Company $company
 * @property User $user
 * @property ProductCategory $productCategory
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProductSubCategory extends BaseModel
{
    
    // static $rules = [
	// 		'company_id' => 'required',
	// 		'name' => 'required|string',
	// 		'description' => 'string',
	// 		'code' => 'required|string',
	// 		'product_category_id' => 'required',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['client_id', 'company_id', 'name', 'description', 'code', 'product_category_id'];
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
    public function productCategory()
    {
        return $this->belongsTo(\App\Models\ProductCategory::class, 'product_category_id', 'id');
    }
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'product_sub_category_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }

    static function store_product_subcategory($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $product_category = ProductSubCategory::create([
           
            // 'name' => $data['name'],
            'name' => $data['name'],
			'description' => $data['description'],
            'is_active' => $data['is_active'],
			'is_default' => $data['is_default'],
			// 'code' => $data['code'],
            'created_by'=>$data['created_by'],
            'product_category_id'=>$data['product_category_id'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
        ]);

        // Event::createEvent(27,$ratelist->id,$ratelist->name,'created','ratelist is created',null,null,$company);



    }
    static function update_product_subcategory($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $productsubCategory->update([
            'name' => $data['name'],
			'description' => $data['description'],
            'is_active' => $data['is_active'],
			'is_default' => $data['is_default'],
			// 'code' => $data['code'],
            'product_category_id'=>$data['product_category_id'],
            'updated_by'=> $data['updated_by']
        ]);

    }


    public static function allSubCategory()
    {
        return self::where('company_id',auth()->user()->active_company())->where('is_active',1)->get();
    }
    

}
