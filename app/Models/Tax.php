<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tax
 *
 * @property $id
 * @property $client_id
 * @property $company_id
 * @property $name
 * @property $rate
 * @property $description
 * @property $is_default
 * @property $is_active
 * @property $valid_from
 * @property $type
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property Client $client
 * @property Company $company
 * @property User $user
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Tax extends Model
{
    
    // static $rules = [
	// 		'company_id' => 'required',
	// 		'name' => 'required|string',
	// 		'rate' => 'required',
	// 		'description' => 'string',
	// 		'is_default' => 'required',
	// 		'is_active' => 'required',
	// 		'type' => 'required|string',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['client_id', 'company_id', 'name', 'rate', 'description', 'is_default', 'is_active', 'valid_from', 'type'];
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
    public function updated_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }
    
    static function store_tax($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $tax = Tax::create([
           
            'name' => $data['name'],
            'description' => $data['description'],
            'rate' => $data['rate'],
			'is_active' => $data['is_active'],
			'valid_from' => $data['valid_from'],
			// 'is_default' => $data['is_default'],
            'created_by'=>$data['created_by'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
        ]);

    }
    static function update_tax($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $tax->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'rate' => $data['rate'],
			'is_active' => $data['is_active'],
			'valid_from' => $data['valid_from'],
			// 'is_default' => $data['is_default'],
            'updated_by'=> $data['updated_by']
        ]);

    }
    public static function Taxes(){
        return Tax::where('company_id',auth()->user()->active_company())->get();
    }

}
