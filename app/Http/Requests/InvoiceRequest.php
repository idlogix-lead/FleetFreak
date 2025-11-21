<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
			'document_no' => 'required|string',
			'company_id' => 'required',
			'vehicle_id' => 'required',
			'business_partner_id' => 'required',
			'date' => 'required',
			'description' => 'string',
			'total_amount' => 'required',
			'grand_total_amount' => 'required',
			'document_status' => 'required',
			'document_type' => 'required',
        ];
    }
}
