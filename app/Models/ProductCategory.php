<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductCategory
 *
 * @property $id
 * @property $client_id
 * @property $company_id
 * @property $name
 * @property $description
 * @property $material_policy
 * @property $default
 * @property $created_at
 * @property $updated_at
 *
 * @property Client $client
 * @property Company $company
 * @property ProductSubCategory[] $productSubCategories
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProductCategory extends BaseModel
{
    
    // static $rules = [
	// 		'company_id' => 'required',
	// 		'name' => 'required|string',
	// 		'description' => 'string',
	// 		'material_policy' => 'string',
	// 		'default' => 'required',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['client_id', 'company_id', 'name', 'description', 'material_policy', 'default'];
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productSubCategories()
    {
        return $this->hasMany(\App\Models\ProductSubCategory::class, 'product_category_id', 'id');
    }
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'product_category_id', 'id');
    }

    static function store_product_category($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $product_category = ProductCategory::create([
           
            // 'name' => $data['name'],
            'name' => $data['name'],
			'description' => $data['description'],
			'material_policy' => $data['material_policy'],
			'default' => $data['default'],
			'is_active' => $data['is_active'],
            'created_by'=>$data['created_by'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
        ]);

        // Event::createEvent(27,$ratelist->id,$ratelist->name,'created','ratelist is created',null,null,$company);



    }
    static function update_product_category($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $productCategory->update([
            'name' => $data['name'],
			'description' => $data['description'],
			'material_policy' => $data['material_policy'],
			'default' => $data['default'],
			'is_active' => $data['is_active'],
            'updated_by'=> $data['updated_by']
        ]);

    }
    public static function allCategory()
    {
        return self::where('company_id',auth()->user()->active_company())->where('is_active',1)->get();
    }
    

}
