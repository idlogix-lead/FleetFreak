<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
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
			'name' => 'required|string',
			'partner_type' => 'required',
			'email' => 'string',
			'phone_no' => 'string',
			'whatsapp_no' => 'string',
			'cnic' => 'required|string',
			'address1' => 'string',
			'address2' => 'string',
			'address3' => 'string',
			'city' => 'string',
			'country' => 'string',
        ];
    }
}
