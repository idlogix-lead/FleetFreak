<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalInventory extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function physicalInvLine()
    {
        return $this->hasMany(\App\Models\PhysicalInventoryLine::class, 'physical_inventory_id', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(\App\Models\WareHouse::class, 'warehouse_id', 'id');
    }


    public static function store_physical_inventory($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);

        $physical_inv = PhysicalInventory::create([
            'document_no' => $document_no,
			'document_status' => $data['document_status'],
			'document_type_id' => $data['document_type_id'],
			'inventory_date' => $data['inventory_date'],
			'description' => $data['description'],
			'warehouse_id' => $data['warehouse_id'],
			'is_active' => $data['is_active'],
            'created_by'=> $data['created_by'],
            'company_id'=> auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id,
        ]);

        foreach($data['rows']??[] as $row){
            PhysicalInventoryLine::create([
                'physical_inventory_id'=> $physical_inv->id,
                'seq_no'=> $row['seq_no'],
                // 'description'=> $row['description'],
                'product_id'=> $row['product_id'],
                'locator_id'=>$row['locator_id'],
                'system_qty'=> $row['system_qty'],
                'physical_qty'=> $row['physical_qty'],
                'adjusted_qty'=> $row['adjusted_qty'],
                'is_active'=>$row['is_active'],
                'created_by'=> $data['created_by'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,
            ]);
            
        }
    }
    public static function update_physical_inventory($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $physical_inventory->update([
			'document_status' => $data['document_status'],
			// 'document_type_id' => $data['document_type_id'],
			'inventory_date' => $data['inventory_date'],
			'description' => $data['description'],
			'warehouse_id' => $data['warehouse_id'],
			'is_active' => $data['is_active'],
            'updated_by'=> $data['updated_by'],
        ]);
        // ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->delete();

        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                MovementLine::where('id', $row['row_id'])->where('physical_inventory_id',$physical_inventory->id)->update([
                    'seq_no'=> $row['seq_no'],
                    // 'description'=> $row['description'],
                    'product_id'=> $row['product_id'],
                    'locator_id'=>$row['locator_id'],
                    'system_qty'=> $row['system_qty'],
                    'physical_qty'=> $row['physical_qty'],
                    'adjusted_qty'=> $row['adjusted_qty'],
                    'is_active'=>$row['is_active'],
                    'company_id'=> auth()->user()->active_company(),
                    'client_id'=>auth()->user()->active_company_details()->client_id,
                    'updated_by'=> $data['updated_by'],
                    
                ]);
            }
            else{

                MovementLine::create([
                'physical_inventory_id'=> $physical_inv->id,
                'seq_no'=> $row['seq_no'],
                // 'description'=> $row['description'],
                'product_id'=> $row['product_id'],
                'locator_id'=>$row['locator_id'],
                'system_qty'=> $row['system_qty'],
                'physical_qty'=> $row['physical_qty'],
                'adjusted_qty'=> $row['adjusted_qty'],
                'is_active'=>$row['is_active'],
                'created_by'=> $data['created_by'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,

                ]);
            }

        }
    }

    public static function generate_no($company_id, $document_type)
    {
        $last = self::where('company_id', $company_id)
        //
            ->where('document_type_id', $document_type->id)
        //
            ->orderByDesc('id')->first();
        if ($last) {
            $document_no = intval(last(explode('-', $last->document_no))) + 1;

        } else {
            $document_no = 1;
        }
        // $prefix =  self::document_no_prefix($document_type);

        return $document_type->code . str_pad($document_no, 4, "0", STR_PAD_LEFT);
    }
}
