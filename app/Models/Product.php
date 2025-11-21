<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $product_image
 * @property $created_by
 * @property $updated_by
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 * @property $company_id
 * @property $cost_price
 * @property $sale_price
 * @property $unit
 *
 * @property Company $company
 * @property User $user
 * @property UnitMeasure $unitType
 * @property User $user
 * @property GlJournalLine[] $glJournalLines
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Product extends BaseModel
{
    use SoftDeletes;

    // static $rules = [
	// 		'name' => 'required|string',
	// 		'description' => 'string',
	// 		'product_image' => 'string',
	// 		'company_id' => 'required',
	// 		'cost_price' => 'required',
	// 		'sale_price' => 'required',
	// 		'unit' => 'required',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'description', 'product_image', 'company_id', 'cost_price', 'sale_price', 'unit'];
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
    public function user_create()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unitType()
    {
        return $this->belongsTo(\App\Models\UnitMeasure::class, 'unit_measure_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_update()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function glJournalLines()
    {
        return $this->hasMany(\App\Models\GlJournalLine::class, 'id', 'product_id');
    }
    public function productPrice()
    {
        return $this->hasMany(\App\Models\ProductPrice::class, 'product_id', 'id');
    }
    static function dropdown(){

        // $user=auth()->user();
        $company=auth()->user()->active_company();

        // if(auth()->user()->actor_id == 2){
            return self::
            where('company_id', $company)->where('is_active',1)
            ->get();
        // }
        // else{
        //     return Partner::when(auth()->user()->partner->actor_id == 4, function($query){
        //         $query
        //         // ->where('partner_type', 'business')
        //         ->where('id',auth()->user()->partner_id);
        //     })->get();
        // }


    }
    public static function store_product($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);

        $product_id = Product::create([
            'name' => $data['name'],
            'sku' => $data['sku_no'],
			'description' => $data['description'],
			'product_category_id' => $data['product_category_id'],
			'product_sub_category_id' => $data['product_sub_category_id'],
			// 'product_image' => ['nullable'],
			// 'cost_price' => ['required'],
			// 'sale_price' => ['required'],
			'unit_measure_id' => $data['unit'],
			'is_default' => $data['is_default'],
			'is_active' => $data['is_active'],
            'created_by'=> $data['created_by'],
            'company_id'=> auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id,
        ]);

        foreach($data['rows']??[] as $row){
            ProductPrice::create([
                'product_id'=> $product_id->id,
                'price_list_version_id'=> $row['price_list_version_id'],
                'seq_no'=> $row['seq_no'],
                'description'=> $row['description'],
                'list_price'=>$row['list_price'],
                'standard_price'=>$row['standard_price'],
                'limit_price'=>$row['limit_price'],
                'is_active'=>$row['is_active'],
                'is_default'=>$row['is_default'],
                'created_by'=> $data['created_by'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,
            ]);
            
        }
    }
    public static function update_product($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $product->update([
            'name' => $data['name'],
            'sku' => $data['sku_no'],
			'description' => $data['description'],
			'product_category_id' => $data['product_category_id'],
			'product_sub_category_id' => $data['product_sub_category_id'],
			// 'product_image' => ['nullable'],
			// 'cost_price' => ['required'],
			// 'sale_price' => ['required'],
			'unit_measure_id' => $data['unit'],
			'is_default' => $data['is_default'],
			'is_active' => $data['is_active'],
            'updated_by'=> $data['updated_by'],
        ]);
        // ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->delete();

        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                ProductPrice::where('id', $row['row_id'])->where('product_id',$product->id)->update([
                'price_list_version_id'=> $row['price_list_version_id'],
                'seq_no'=> $row['seq_no'],
                'description'=> $row['description'],
                'list_price'=>$row['list_price'],
                'standard_price'=>$row['standard_price'],
                'limit_price'=>$row['limit_price'],
                'is_active'=>$row['is_active'],
                'is_default'=>$row['is_default'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,
                'updated_by'=> $data['updated_by'],
                    
                ]);
            }
            else{

                ProductPrice::create([
                'product_id'=> $product->id,
                'price_list_version_id'=> $row['price_list_version_id'],
                'seq_no'=> $row['seq_no'],
                'description'=> $row['description'],
                'list_price'=>$row['list_price'],
                'standard_price'=>$row['standard_price'],
                'limit_price'=>$row['limit_price'],
                'is_active'=>$row['is_active'],
                'is_default'=>$row['is_default'],
                'created_by'=> auth()->user()->id,
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,

                ]);
            }

        }
    }



}
