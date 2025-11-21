<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Locator
 *
 * @property $id
 * @property $client_id
 * @property $company_id
 * @property $warehouse_id
 * @property $code
 * @property $locator_type
 * @property $is_active
 * @property $is_default
 * @property $relative_priority
 * @property $aisle
 * @property $bin
 * @property $level
 * @property $created_at
 * @property $updated_at
 *
 * @property Client $client
 * @property Company $company
 * @property WareHouse $wareHouse
 * @property MaterialInoutLine[] $materialInoutLines
 * @property WareHouse[] $wareHouses
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Locator extends BaseModel
{
    
    // static $rules = [
	// 		'company_id' => 'required',
	// 		'warehouse_id' => 'required',
	// 		'code' => 'required|string',
	// 		'locator_type' => 'string',
	// 		'is_active' => 'required',
	// 		'is_default' => 'required',
	// 		'relative_priority' => 'string',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['client_id', 'company_id', 'warehouse_id', 'code', 'locator_type', 'is_active', 'is_default', 'relative_priority', 'aisle', 'bin', 'level'];
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
    public function wareHouse()
    {
        return $this->belongsTo(\App\Models\WareHouse::class, 'warehouse_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materialInoutLines()
    {
        return $this->hasMany(\App\Models\MaterialInoutLine::class, 'id', 'locator_id');
    }
    

    /**
     * Get all locators.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all_locators()
    {
        return self::where('company_id',auth()->user()->active_company())->get();
    }

    static function store_locator($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $wareHouse = Locator::create([
           
            // 'name' => $data['name'],
            'warehouse_id' => $data['warehouse_id'],
			'code' => $data['code'],
			'locator_type' => $data['locator_type'],
			'is_active' => $data['is_active'],
			'is_default' => $data['is_default'],
			'relative_priority' => $data['relative_priority'],
			'aisle' => $data['aisle'], 
			'bin' => $data['bin'],
			'level' => $data['level'],
            'created_by'=>$data['created_by'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
        ]);

        // Event::createEvent(27,$ratelist->id,$ratelist->name,'created','ratelist is created',null,null,$company);



    }
    static function update_locator($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $locator->update([
            'warehouse_id' => $data['warehouse_id'],
			'code' => $data['code'],
			'locator_type' => $data['locator_type'],
			'is_active' => $data['is_active'],
			'is_default' => $data['is_default'],
			'relative_priority' => $data['relative_priority'],
			'aisle' => $data['aisle'], 
			'bin' => $data['bin'],
			'level' => $data['level'],
            'updated_by'=> $data['updated_by']
        ]);

    }

    public static function locators()
    {
        return self::where('company_id',auth()->user()->active_company())->where('is_active',1)->get();
    }
    

}
