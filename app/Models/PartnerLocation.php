<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartnerLocation
 *
 * @property $id
 * @property $partner_id
 * @property $address1
 * @property $address2
 * @property $address3
 * @property $primary_contact_person
 * @property $secondary_contact_person
 * @property $prefix_phone
 * @property $phone_no
 * @property $prefix_whatsapp
 * @property $whatsapp_no
 * @property $city
 * @property $country
 * @property $is_default
 * @property $ship_address
 * @property $invoice_address
 * @property $created_at
 * @property $updated_at
 *
 * @property Partner $partner
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PartnerLocation extends BaseModel
{
    
    // static $rules = [
	// 		'partner_id' => 'required',
	// 		'address1' => 'string',
	// 		'address2' => 'string',
	// 		'address3' => 'string',
	// 		'primary_contact_person' => 'string',
	// 		'secondary_contact_person' => 'string',
	// 		'prefix_phone' => 'string',
	// 		'phone_no' => 'string',
	// 		'prefix_whatsapp' => 'string',
	// 		'whatsapp_no' => 'string',
	// 		'city' => 'string',
	// 		'country' => 'string',
	// 		'is_default' => 'required',
	// 		'ship_address' => 'required',
	// 		'invoice_address' => 'required',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['partner_id', 'address1', 'address2', 'address3', 'primary_contact_person', 'secondary_contact_person', 'prefix_phone', 'phone_no', 'prefix_whatsapp', 'whatsapp_no', 'city', 'country', 'is_default', 'ship_address', 'invoice_address'];
    protected $guarded = [];

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'partner_id', 'id');
    }

    static function store_partner_location($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }

        $user=auth()->user();
        $company=auth()->user()->active_company() ?? null;

        $partner_loc = PartnerLocation::create([
           
            'partner_id' => $partner_location_data['partner_id'],
            'address1' => $partner_location_data['address1'],
            'primary_contact_person' => $partner_location_data['primary_contact_person'],
            'secondary_contact_person' => $partner_location_data['secondary_contact_person'],
            'prefix_phone'=>$partner_location_data['prefix_phone']?? null,
            'phone_no' => $partner_location_data['phone_no'],
            'prefix_whatsapp'=>$partner_location_data['prefix_whatsapp'],
            'whatsapp_no' => $partner_location_data['whatsapp_no'],
            'ship_address' => $partner_location_data['ship_address'],
            'invoice_address' => $partner_location_data['invoice_address'],
            // 'address2' => $partner_data['address2']?? null,
            // 'address3' => $partner_data['address3']?? null,
            'city' => $partner_location_data['city'],
            'country' => $partner_location_data['country'],
            'is_default' => $partner_location_data['is_default'],
            'created_by'=>$partner_location_data['created_by'],
            'company_id'=>auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id
        ]);
        Partner::where('id',$partner_location_data['partner_id'])->update(['partner_loc_id'=>$partner_loc->id]);

        // Event::createEvent(27,$ratelist->id,$ratelist->name,'created','ratelist is created',null,null,$company);



    }
    static function update_partner_location($payload){
        foreach($payload as $key => $val){
            $$key = $val;
        }
        PartnerLocation::where('id',$partner_loc->id)->update([
           'partner_id' => $partner_location_data['partner_id'],
            'address1' => $partner_location_data['address1'],
            'primary_contact_person' => $partner_location_data['primary_contact_person'],
            'secondary_contact_person' => $partner_location_data['secondary_contact_person'],
            'prefix_phone'=>$partner_location_data['prefix_phone']?? null,
            'phone_no' => $partner_location_data['phone_no'],
            'prefix_whatsapp'=>$partner_location_data['prefix_whatsapp'],
            'whatsapp_no' => $partner_location_data['whatsapp_no'],
            'ship_address' => $partner_location_data['ship_address'],
            'invoice_address' => $partner_location_data['invoice_address'],
            // 'address2' => $partner_data['address2']?? null,
            // 'address3' => $partner_data['address3']?? null,
            'city' => $partner_location_data['city'],
            'country' => $partner_location_data['country'],
            'is_default' => $partner_location_data['is_default'],
            'updated_by'=> $partner_location_data['updated_by']
        ]);

    }
    

}
