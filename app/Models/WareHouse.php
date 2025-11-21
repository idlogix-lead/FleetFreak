<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WareHouse
 *
 * @property $id
 * @property $client_id
 * @property $company_id
 * @property $name
 * @property $description
 * @property $code
 * @property $is_active
 * @property $in_transit
 * @property $address
 * @property $source_warehouse_id
 * @property $is_disallow_negative_inv
 * @property $created_at
 * @property $updated_at
 * @property $locator_id
 *
 * @property Client $client
 * @property Company $company
 * @property Locator $locator
 * @property WareHouse $wareHouse
 * @property Locator[] $locators
 * @property MaterialInout[] $materialInouts
 * @property Order[] $orders
 * @property WareHouse[] $wareHouses
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class WareHouse extends BaseModel
{
    
    // static $rules = [
	// 		'company_id' => 'required',
	// 		'name' => 'required|string',
	// 		'description' => 'string',
	// 		'code' => 'string',
	// 		'is_active' => 'required',
	// 		'in_transit' => 'required',
	// 		'address' => 'string',
	// 		'is_disallow_negative_inv' => 'required',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['client_id', 'company_id', 'name', 'description', 'code', 'is_active', 'in_transit', 'address', 'source_warehouse_id', 'is_disallow_negative_inv', 'locator_id'];
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
    public function locator()
    {
        return $this->belongsTo(\App\Models\Locator::class, 'locator_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wareHouse()
    {
        return $this->belongsTo(\App\Models\WareHouse::class, 'source_warehouse_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locators()
    {
        return $this->hasMany(\App\Models\Locator::class,'warehouse_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materialInouts()
    {
        return $this->hasMany(\App\Models\MaterialInout::class, 'id', 'warehouse_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'id', 'warehouse_id');
    }
    
   
    static function store_warehouse($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $wareHouse = WareHouse::create([
           
            'name' => $data['name'],
            'description' => $data['description'],
            'code' => $data['code'],
            'is_active' => $data['is_active'],
            'is_default' => $data['is_default'],
			'in_transit' => $data['in_transit'],
			'address' => $data['address'],
            // 'locator_id'=> $data['locator_id'],
            'source_warehouse_id'=> $data['source_warehouse_id'],
			'is_disallow_negative_inv' => $data['is_disallow_negative_inv'],
            'created_by'=>$data['created_by'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
        ]);
        foreach($data['rows']??[] as $row){
            Locator::create([
            'warehouse_id' => $wareHouse->id,
			'code' => $row['code'],
			'locator_type' => $row['locator_type'],
			'is_active' => $row['is_active'],
			'is_default' => $row['is_default'],
			'relative_priority' => $row['relative_priority'],
			'aisle' => $row['aisle'], 
			'bin' => $row['bin'],
			'level' => $row['level'],
            'created_by'=>$data['created_by'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
            ]);
        }

        // Event::createEvent(27,$ratelist->id,$ratelist->name,'created','ratelist is created',null,null,$company);



    }
    static function update_warehouse($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $warehouse->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'code' => $data['code'],
            'is_active' => $data['is_active'],
            'is_default' => $data['is_default'],
			'in_transit' => $data['in_transit'],
			'address' => $data['address'],
            // 'locator_id'=> $data['locator_id'],
            'source_warehouse_id'=> $data['source_warehouse_id'],
			'is_disallow_negative_inv' => $data['is_disallow_negative_inv'],
            'updated_by'=> $data['updated_by']
        ]);
        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                Locator::where('id', $row['row_id'])->where('warehouse_id',$warehouse->id)->update([
                // 'seq_no'=>$row['seq_no'],
                'code' => $row['code'],
                'locator_type' => $row['locator_type'],
                'is_active' => $row['is_active'],
                'is_default' => $row['is_default'],
                'relative_priority' => $row['relative_priority'],
                'aisle' => $row['aisle'], 
                'bin' => $row['bin'],
                'level' => $row['level'],
                'updated_by'=> $data['updated_by']
                ]);
            }
            else{

                Locator::create([
                    'warehouse_id' => $warehouse->id,
                    'code' => $row['code'],
                    'locator_type' => $row['locator_type'],
                    'is_active' => $row['is_active'],
                    'is_default' => $row['is_default'],
                    'relative_priority' => $row['relative_priority'],
                    'aisle' => $row['aisle'], 
                    'bin' => $row['bin'],
                    'level' => $row['level'],
                    'created_by'=>auth()->user()->id,
                    'company_id'=>auth()->user()->active_company(),
                    'client_id'=>auth()->user()->active_company_details()->client_id

                ]);
            }

        }

    }

    static function warehouses(){
        return self::where('company_id',auth()->user()->active_company())->where('is_active',1)->get();
    }
    
    

}
