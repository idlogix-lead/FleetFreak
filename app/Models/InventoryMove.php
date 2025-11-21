<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryMove extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public static function store_inventory_move($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);

        $inventory_move = InventoryMove::create([
            'document_no' => $document_no,
			'document_status' => $data['document_status'],
			'document_type_id' => $data['document_type_id'],
			'movement_date' => $data['movement_date'],
			'description' => $data['description'],
			'locator_from' => $data['locator_from'],
			'locator_to' => $data['locator_to'],
			// 'is_default' => $data['is_default'],
			'is_active' => $data['is_active'],
            'created_by'=> $data['created_by'],
            'company_id'=> auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id,
        ]);

        foreach($data['rows']??[] as $row){
            MovementLine::create([
                'inventory_move_id'=> $inventory_move->id,
                'seq_no'=> $row['seq_no'],
                // 'description'=> $row['description'],
                'product_id'=> $row['product_id'],
                'movement_qty'=> $row['movement_qty'],
                'locator_from'=>$row['locator_from'],
                'locator_to'=> $row['locator_to'],
                'is_active'=>$row['is_active'],
                'created_by'=> $data['created_by'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,
            ]);
            if($data['document_status']!='draft'){
                $stockStorageFrom = StockStorage::where('product_id',$row['product_id'])->where('locator_id',$row['locator_from'])->first();
                $stockStorageFrom->update(['on_hand_qty' => DB::raw("on_hand_qty - {$row['movement_qty']}")]);
                $stockStorageFrom->refresh();
                $stockStorageTo = StockStorage::where('product_id',$row['product_id'])->where('locator_id',$row['locator_to'])->first();
                $stockStorageTo->update(['on_hand_qty' => DB::raw("on_hand_qty + {$row['movement_qty']}")]);

            }
            
        }
    }
    public static function update_inventory_move($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $inventory_move->update([
			'document_status' => $data['document_status'],
			'movement_date' => $data['movement_date'],
			'description' => $data['description'],
			'locator_from' => $data['locator_from'],
			'locator_to' => $data['locator_to'],
			'is_default' => $data['is_default'],
			'is_active' => $data['is_active'],
            'updated_by'=> $data['updated_by'],
        ]);
        // ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->delete();

        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                MovementLine::where('id', $row['row_id'])->where('inventory_move_id',$inventory_move->id)->update([
                    // 'description'=> $row['description'],
                    'product_id'=> $row['product_id'],
                    'movement_qty'=> $row['movement_qty'],
                    'locator_from'=>$row['locator_from'],
                    'locator_to'=> $row['locator_to'],
                    'is_active'=>$row['is_active'],
                    'company_id'=> auth()->user()->active_company(),
                    'client_id'=>auth()->user()->active_company_details()->client_id,
                    'updated_by'=> $data['updated_by'],
                    
                ]);
            }
            else{

                MovementLine::create([
                    'inventory_move_id'=> $inventory_move->id,
                    'seq_no'=> $row['seq_no'],
                    // 'description'=> $row['description'],
                    'product_id'=> $row['product_id'],
                    'movement_qty'=> $row['movement_qty'],
                    'locator_from'=>$row['locator_from'],
                    'locator_to'=> $row['locator_to'],
                    'is_active'=>$row['is_active'],
                    'created_by'=> auth()->user()->id,
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

    public function movementLine()
    {
        return $this->hasMany(MovementLine::class,'inventory_move_id','id');
    }
}
