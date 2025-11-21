<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UnitMeasure
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $company_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Company $company
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class UnitMeasure extends BaseModel
{

    // static $rules = [
	// 		'name' => 'required',
	// 		'description' => 'nullable',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'description', 'company_id'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');
    }
    public static function unit_type()
    {
        $company_id = auth()->user()->active_company();
        $unit_types = self::where('company_id', $company_id)->get();
        // dd($unit_types);
        return $unit_types;
    }

    public static function measurementSymbols()
    {
        $symbols = [
            'kg' => 'Kilogram',
            'g' => 'Gram',
            'lb' => 'Pound',
            'oz' => 'Ounce',
            'l' => 'Liter',
            'ml' => 'Milliliter',
            'm' => 'Meter',
            'cm' => 'Centimeter',
            'mm' => 'Millimeter',
            't'=> 'Metric Ton'
        ];
        return array_keys($symbols);
    }

    static function store_unit_of_measure($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $manu_comp = UnitMeasure::create([
           
            'name' => $data['name'],
            'description' => $data['description'],
            'UNCEFACT_code'=>$data['uncefact_code'],
            // 'uom_code'=>$data['uom_code'],
            'symbol'=>$data['symbol'],
            'uom_type'=>$data['uom_type'],
            'is_active'=>$data['is_active'],
            'is_default'=>$data['is_default'],
            'std_precision'=>$data['std_precision'],
            'cost_precision'=>$data['cost_precision'],
            'created_by'=>$data['created_by'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
        ]);

        // Event::createEvent(27,$ratelist->id,$ratelist->name,'created','ratelist is created',null,null,$company);



    }
    static function update_unit_of_measure($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        $unit_measure->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'UNCEFACT_code'=>$data['uncefact_code'],
            // 'uom_code'=>$data['uom_code'],
            'symbol'=>$data['symbol'],
            'uom_type'=>$data['uom_type'],
            'is_active'=>$data['is_active'],
            'is_default'=>$data['is_default'],
            'std_precision'=>$data['std_precision'],
            'cost_precision'=>$data['cost_precision'],
            'updated_by'=>$data['updated_by'],

        ]);

    }

}
