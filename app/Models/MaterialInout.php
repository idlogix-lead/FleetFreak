<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MaterialInout extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function materialInoutline()
    {
        return $this->hasMany(MaterialInoutLine::class,'m_inout_id','id');
    }
    public function partner_business()
    {
        return $this->belongsTo(Partner::class,'business_partner_id','id');
    }
    public function wareHouse()
    {
        return $this->belongsTo(WareHouse::class,'warehouse_id','id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }


    public static function store_material_inout($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);

        $material_inout = MaterialInout::create([
            'document_no'=>$document_no,
            'order_id'=>$data['order_id'],
            'po_reference'=>$data['po_reference']??null,
            'description'=>$data['description'],
            'document_type'=>$data['document_type_id'],
            'date_ordered'=>$data['date_ordered'],
            'movement_date'=>$data['movement_date'],
            // 'accounting_date'=>$data['accounting_date'],
            'business_partner_id'=>$data['business_partner_id'],
            'partner_location_id'=>$data['partner_location_id'],
            'warehouse_id'=>$data['warehouse_id'],
            'delivery_man'=>$data['delivery_man'],
            'delivery_vehicle'=>$data['delivery_vehicle'],
            'delivery_no'=>$data['delivery_no'],
            'delivery_time'=>$data['delivery_time'],
            // 'gate_inout'=>$data['gate_inout'],
            // 'create_lines_from'=>$data['create_lines_from'],
            // 'c_l_from_gatepass'=>$data['c_l_from_gatepass'],
            'document_action'=>$data['document_action'],
            'document_status'=>$data['document_status'],
            // 'rma'=>$data['rma'],
            'user_id'=>auth()->user()->id,
            'created_by'=> $data['created_by'],
            'company_id'=> auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id,
        ]);

        foreach($data['rows']??[] as $row){
            $material_line = MaterialInoutLine::create([
                'm_inout_id'=> $material_inout->id,
                // 'status'=> $data['document_status'],
                'order_detail_line_id'=> $row['order_detail_line_id'],
                'status'=> 'pending',
                'line_no'=> $row['seq_no'],
                'product_id'=>$row['product_id'],
                'locator_id'=>$row['locator_id'],
                // 'description'=>$row['description'],
                'quantity'=>$row['quantity'],
                'movement_qty'=>$row['movement_qty'],
                'picked_qty'=>$row['picked_qty'],
                'target_qty'=>$row['target_qty'],
                'confirmed_qty'=>$row['confirmed_qty'],
                'scrapped_qty'=>$row['scrapped_qty'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,
            ]);
            if($data['document_status']!='draft' && $row['order_detail_line_id']){
                $order_detail = OrderDetail::where('id',$row['order_detail_line_id'])->first();
                $order_detail->update(['delivered_qty' => DB::raw("delivered_qty + {$row['movement_qty']}"),'order_qty'=>DB::raw("order_qty - {$row['movement_qty']}")]);
                $order_detail->refresh();
                if (intval($order_detail->order_qty) === 0) {
                    $order_detail->update(['status' => 'completed']);
                }
                self::updateOrCreateStockStorage($row['locator_id'], $row['product_id'], $row['movement_qty']);
                self::updateOrCreateProductCosting($order_detail['rate'], $row['product_id'], $row['movement_qty']);
                // hitting account transactions
                $debit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Product Stock')->first();
                $credit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Not Invoiced Receipt')->first();
                $currency = User::current_currency();
                AccountTransaction::createTransaction($data['movement_date'], auth()->user()->active_company(), $debit_account->id, $credit_account->id,$row['movement_qty'], (float)$order_detail->rate*$row['movement_qty'], $currency->id, $material_inout->id, $material_line->id, null, 65,business_partner_id:$data['business_partner_id']);
                // end here
                // product inventory clearing account debit
                // accounts payable credit 
            }
            
        }
    }
    private static function updateOrCreateStockStorage($locatorId, $productId, $movement_qty)
    {
        $stockStorage = StockStorage::where('locator_id', $locatorId)->where('company_id', auth()->user()->active_company())
            ->where('product_id', $productId)
            ->first();

        if ($stockStorage) {
            // If stock record exists, update the quantity
            $stockStorage->increment('on_hand_qty', $movement_qty);
        } else {
            // If stock record doesn't exist, create a new one
            StockStorage::create([
                'locator_id' => $locatorId,
                'product_id' => $productId,
                'on_hand_qty' => $movement_qty,
                'created_by'=> auth()->user()->id,
                'company_id' => auth()->user()->active_company(),
                'client_id' => auth()->user()->active_company_details()->client_id,
            ]);
        }
    }
    private static function updateOrCreateProductCosting($currentRate, $productId, $movement_qty)
    {
        $productCosting = ProductCosting::where('company_id', auth()->user()->active_company())
            ->where('product_id', $productId)
            ->first();

        if ($productCosting) {
            // Calculate new weighted average cost
            $existing_qty = $productCosting->current_qty;
            $existing_cost = $productCosting->current_cost;

            $new_qty = $movement_qty;
            $new_cost = $currentRate;

            // Weighted average cost formula
            $updated_qty = $existing_qty + $new_qty;
            $updated_cost = (($existing_qty * $existing_cost) + ($new_qty * $new_cost)) / $updated_qty;

            // Update product costing
            $productCosting->update([
                'current_cost' => $updated_cost,
                'current_qty' => $updated_qty,
                'updated_by'=> auth()->user()->id,
            ]);
        } else {
            // If stock record doesn't exist, create a new one
            ProductCosting::create([
                'product_id' => $productId,
                'current_cost' => $currentRate,
                'current_qty' => $movement_qty,
                'created_by'=> auth()->user()->id,
                'company_id' => auth()->user()->active_company(),
                'client_id' => auth()->user()->active_company_details()->client_id,
            ]);
        }
    }
    public static function update_material_inout($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $material_inout->update([
        //   'order_no'=>$document_no,
            'po_reference'=>$data['po_reference']??null,
            'description'=>$data['description'],
            // 'document_type_id'=>$data['document_type_id'],
            'date_ordered'=>$data['date_ordered'],
            'movement_date'=>$data['movement_date'],
            // 'accounting_date'=>$data['accounting_date'],
            'business_partner_id'=>$data['business_partner_id'],
            'partner_location_id'=>$data['partner_location_id'],
            'warehouse_id'=>$data['warehouse_id'],
            'delivery_man'=>$data['delivery_man'],
            'delivery_vehicle'=>$data['delivery_vehicle'],
            'delivery_no'=>$data['delivery_no'],
            'delivery_time'=>$data['delivery_time'],
            // 'gate_inout'=>$data['gate_inout'],
            // 'create_lines_from'=>$data['create_lines_from'],
            // 'c_l_from_gatepass'=>$data['c_l_from_gatepass'],
            'document_action'=>$data['document_action'],
            'document_status'=>$data['document_status'], 
            // 'rma'=>$data['rma'],        
            'updated_by'=> $data['updated_by']
        ]);
        // ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->delete();

        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                $material_line = MaterialInoutLine::where('id', $row['row_id'])->where('m_inout_id',$material_inout->id)->update([
                    // 'status'=> $data['document_status'],
                    'order_detail_line_id'=> $row['order_detail_line_id'],
                    'product_id'=>$row['product_id'],
                    'status'=> 'pending',
                    'locator_id'=>$row['locator_id'],
                    // 'description'=>$row['description'],
                    'quantity'=>$row['quantity'],
                    'movement_qty'=>$row['movement_qty'],
                    'picked_qty'=>$row['picked_qty'],
                    'target_qty'=>$row['target_qty'],
                    'confirmed_qty'=>$row['confirmed_qty'],
                    'scrapped_qty'=>$row['scrapped_qty'],
                    'company_id'=> auth()->user()->active_company(),
                    'client_id'=>auth()->user()->active_company_details()->client_id,
                ]);
                // if($data['document_status']!='draft' && $row['order_detail_line_id']){
                //     OrderDetail::where('id',$row['order_detail_line_id'])->update(['status'=>$data['document_status'],'delivered_qty'=>$row['quantity']]);
                // }
                if($data['document_status']!='draft' && $row['order_detail_line_id']){
                    $order_detail = OrderDetail::where('id',$row['order_detail_line_id'])->first();
                    $order_detail->update(['delivered_qty' => DB::raw("delivered_qty + {$row['movement_qty']}"),'order_qty'=>DB::raw("order_qty - {$row['movement_qty']}")]);
                    $order_detail->refresh();
                    if (intval($order_detail->order_qty) === 0) {
                        $order_detail->update(['status' => 'completed']);
                    }
                    self::updateOrCreateStockStorage($row['locator_id'], $row['product_id'], $row['movement_qty']);
                    self::updateOrCreateProductCosting($order_detail['rate'], $row['product_id'], $row['movement_qty']);
                     // hitting account transactions
                    $debit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Product Stock')->first();
                    $credit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Not Invoiced Receipt')->first();
                    $currency = User::current_currency();
                    AccountTransaction::createTransaction($data['movement_date'], auth()->user()->active_company(), $debit_account->id, $credit_account->id,$row['movement_qty'], (float)$order_detail->rate*$row['movement_qty'], $currency->id, $material_inout->id, $material_line->id, null, 65,business_partner_id:$data['business_partner_id']);
                    // end here
                }
            }
            else{

                $material_line = MaterialInoutLine::create([
                    'm_inout_id'=> $material_inout->id,
                    // 'status'=> $data['document_status'],
                    'order_detail_line_id'=> $row['order_detail_line_id'],
                    'product_id'=>$row['product_id'],
                    'locator_id'=>$row['locator_id'],
                    // 'description'=>$row['description'],
                    'quantity'=>$row['quantity'],
                    'movement_qty'=>$row['movement_qty'],
                    'picked_qty'=>$row['picked_qty'],
                    'target_qty'=>$row['target_qty'],
                    'confirmed_qty'=>$row['confirmed_qty'],
                    'scrapped_qty'=>$row['scrapped_qty'],
                    'company_id'=> auth()->user()->active_company(),
                    'client_id'=>auth()->user()->active_company_details()->client_id,

                ]);
                if($data['document_status']!='draft' && $row['order_detail_line_id']){
                    $order_detail = OrderDetail::where('id',$row['order_detail_line_id'])->first();
                    $order_detail->update(['delivered_qty' => DB::raw("delivered_qty + {$row['movement_qty']}"),'order_qty'=>DB::raw("order_qty - {$row['movement_qty']}")]);
                    $order_detail->refresh();
                    if (intval($order_detail->order_qty) === 0) {
                        $order_detail->update(['status' => 'completed']);
                    }
                    self::updateOrCreateStockStorage($row['locator_id'], $row['product_id'], $row['movement_qty']);
                    self::updateOrCreateProductCosting($order_detail['rate'], $row['product_id'], $row['movement_qty']);
                     // hitting account transactions
                    $debit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Product Stock')->first();
                    $credit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Not Invoiced Receipt')->first();
                    $currency = User::current_currency();
                    AccountTransaction::createTransaction($data['movement_date'], auth()->user()->active_company(), $debit_account->id, $credit_account->id,$row['movement_qty'], (float)$order_detail->rate*$row['movement_qty'], $currency->id, $material_inout->id, $material_line->id, null, 65,business_partner_id:$data['business_partner_id']);
                    // end here
                }
                // if($data['document_status']!='draft' && $row['order_detail_line_id']){
                //     OrderDetail::where('id',$row['order_detail_line_id'])->update([
                //         'status'=>$data['document_status'],
                //         'delivered_qty'=>$row['quantity'],
                //         'order_qty'=>DB::raw("order_qty - {$row['movement_qty']}"),
                //     ]);
                // }
            }

        }
    }



    public static function generate_document_no2($company_id, $document_type)
    {
        $last = self::where('company_id', $company_id)
        //
            ->where('document_type', $document_type->id)
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
