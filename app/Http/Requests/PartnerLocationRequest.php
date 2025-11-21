<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'partner_id' => 'required',
			'address1' => 'string',
			'address2' => 'string',
			'address3' => 'string',
			'primary_contact_person' => 'string',
			'secondary_contact_person' => 'string',
			'prefix_phone' => 'string',
			'phone_no' => 'string',
			'prefix_whatsapp' => 'string',
			'whatsapp_no' => 'string',
			'city' => 'string',
			'country' => 'string',
			'is_default' => 'required',
			'ship_address' => 'required',
			'invoice_address' => 'required',
        ];
    }
}
