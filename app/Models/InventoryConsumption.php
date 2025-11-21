<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryConsumption extends BaseModel
{
    use HasFactory;
    protected $guarded = [];
    public function internalUseLine()
    {
        return $this->hasMany(InternalUseline::class,'inventory_consumption_id','id');
    }

    public static function store_inventory_consumptions($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);

        $inventory_consumption = InventoryConsumption::create([
            'document_no' => $document_no,
			'document_status' => $data['document_status'],
			'document_type_id' => $data['document_type_id'],
			'movement_date' => $data['movement_date'],
			'description' => $data['description'],
			'warehouse_id' => $data['warehouse_id'],
			// 'is_default' => $data['is_default'],
			'is_active' => $data['is_active'],
            'created_by'=> $data['created_by'],
            'company_id'=> auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id,
        ]);

        foreach($data['rows']??[] as $row){
            InternalUseline::create([
                'inventory_consumption_id'=> $inventory_consumption->id,
                'seq_no'=> $row['seq_no'],
                // 'description'=> $row['description'],
                'product_id'=> $row['product_id'],
                'movement_qty'=> $row['movement_qty'],
                'locator_id'=>$row['locator_id'],
                'account_id'=> $row['account_id'],
                'is_active'=>$row['is_active'],
                'created_by'=> $data['created_by'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,
            ]);
            
        }
        if($data['document_status']!='draft'){
            $stockStorageFrom = StockStorage::where('product_id',$row['product_id'])->where('locator_id',$row['locator_id'])->first();
            $stockStorageFrom->update(['on_hand_qty' => DB::raw("on_hand_qty - {$row['movement_qty']}")]);
            $productCosting = ProductCosting::where('product_id',$row['product_id'])->first();
            $productCosting->update(['current_qty' => DB::raw("current_qty - {$row['movement_qty']}")]);
             // hitting account transactions
             $product_price = ProductPrice::where('product_id', $row['product_id'])->where('company_id', auth()->user()->active_company())->where('is_active', 1)->where('is_default', 1)->first();
             $debit_account = Account::where('company_id', auth()->user()->active_company())->where('id', $row['account_id'])->first();
             $credit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Product Stock')->first();
             $currency = User::current_currency();
             AccountTransaction::createTransaction($data['movement_date'], auth()->user()->active_company(), $debit_account->id, $credit_account->id,$row['movement_qty'], (float)$product_price->standard_price, $currency->id, $material_inout->id, $material_line->id, null, 65,business_partner_id:$data['business_partner_id']);
             // end here

        }
    }

    public static function update_inventory_consumptions($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $inventory_consumptions->update([
			'document_status' => $data['document_status'],
			'movement_date' => $data['movement_date'],
			'description' => $data['description'],
			'warehouse_id' => $data['warehouse_id'],
			// 'is_default' => $data['is_default'],
			'is_active' => $data['is_active'],
            'updated_by'=> $data['updated_by'],
        ]);
        // ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->delete();

        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                InternalUseline::where('id', $row['row_id'])->where('inventory_consumption_id',$inventory_consumptions->id)->update([
                    // 'description'=> $row['description'],
                    'product_id'=> $row['product_id'],
                    'movement_qty'=> $row['movement_qty'],
                    'locator_id'=>$row['locator_id'],
                    'account_id'=> $row['account_id'],
                    'is_active'=>$row['is_active'],
                    'company_id'=> auth()->user()->active_company(),
                    'client_id'=>auth()->user()->active_company_details()->client_id,
                    'updated_by'=> $data['updated_by'],
                    
                ]);
            }
            else{

                InternalUseline::create([
                    'inventory_consumption_id'=> $inventory_consumptions->id,
                    'seq_no'=> $row['seq_no'],
                    // 'description'=> $row['description'],
                    'product_id'=> $row['product_id'],
                    'movement_qty'=> $row['movement_qty'],
                    'locator_id'=>$row['locator_id'],
                    'account_id'=> $row['account_id'],
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


}
