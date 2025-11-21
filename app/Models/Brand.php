<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Brand
 *
 * @property $id
 * @property $client_id
 * @property $company_id
 * @property $name
 * @property $description
 * @property $code
 * @property $created_at
 * @property $updated_at
 *
 * @property Client $client
 * @property Company $company
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Brand extends BaseModel
{
    
    // static $rules = [
	// 		'company_id' => 'required',
	// 		'name' => 'required|string',
	// 		'description' => 'string',
	// 		'code' => 'string',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['client_id', 'company_id', 'name', 'description', 'code'];
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
    
    static function store_brands($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $brand = Brand::create([
           
            'name' => $data['name'],
            'description' => $data['description'],
            'code' => $data['code'],
			'is_active' => $data['is_active'],
			'is_default' => $data['is_default'],

            'created_by'=>$data['created_by'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
        ]);

        // Event::createEvent(27,$ratelist->id,$ratelist->name,'created','ratelist is created',null,null,$company);



    }
    static function update_brands($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $brands->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'code' => $data['code'],
			'is_active' => $data['is_active'],
			'is_default' => $data['is_default'],
            'updated_by'=> $data['updated_by']
        ]);

    }

}
