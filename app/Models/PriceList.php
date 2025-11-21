<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function priceListVersion()
    {
        return $this->hasMany(\App\Models\PriceListVersion::class, 'price_list_id', 'id');
    }


    public static function store_price_list($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);

        $price_list = PriceList::create([
            'name' => $data['name'],
			'description' => $data['description'],
			'currency' => $data['currency'],
			'price_precision' => $data['price_precision'],
			'sales_price_list' => $data['sales_price_list'],
			'price_includes_tax' => $data['price_includes_tax'],
			'enforce_price_limit' => $data['enforce_price_limit'],
			'is_default' => $data['is_default'],
			'is_active' => $data['is_active'],
            'created_by'=> $data['created_by'],
            'company_id'=> auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id,
        ]);

        foreach($data['rows']??[] as $row){
            PriceListVersion::create([
                'price_list_id'=> $price_list->id,
                'seq_no'=> $row['seq_no'],
                'name'=> $row['name'],
                'description'=> $row['description'],
                'valid_from'=>$row['valid_from'],
                'is_active'=>$row['is_active'],
                'created_by'=> $data['created_by'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,
            ]);
            
        }
    }
    public static function update_price_list($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $price_list->update([
            'name' => $data['name'],
			'description' => $data['description'],
			'currency' => $data['currency'],
			'price_precision' => $data['price_precision'],
			'sales_price_list' => $data['sales_price_list'],
			'price_includes_tax' => $data['price_includes_tax'],
			'enforce_price_limit' => $data['enforce_price_limit'],
			'is_default' => $data['is_default'],
			'is_active' => $data['is_active'],
            'updated_by'=> $data['updated_by'],
        ]);
        // ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->delete();

        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                PriceListVersion::where('id', $row['row_id'])->where('price_list_id',$price_list->id)->update([
                    'name'=> $row['name'],
                    'description'=> $row['description'],
                    'valid_from'=>$row['valid_from'],
                    'is_active'=>$row['is_active'],
                    'company_id'=> auth()->user()->active_company(),
                    'client_id'=>auth()->user()->active_company_details()->client_id,
                    'updated_by'=> $data['updated_by'],
                    
                ]);
            }
            else{

                PriceListVersion::create([
                    'price_list_id'=> $price_list->id,
                    'seq_no'=> $row['seq_no'],
                    'name'=> $row['name'],
                    'description'=> $row['description'],
                    'valid_from'=>$row['valid_from'],
                    'is_active'=>$row['is_active'],
                    'created_by'=> auth()->user()->id,
                    'company_id'=> auth()->user()->active_company(),
                    'client_id'=>auth()->user()->active_company_details()->client_id,

                ]);
            }

        }
    }

    public static function pricelist(){
        return self::where('company_id',auth()->user()->active_company())->where('is_active',1)->get();
    }
}
