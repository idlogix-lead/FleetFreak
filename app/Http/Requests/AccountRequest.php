<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
			'code' => 'required|string',
			'description' => 'string',
			'is_active' => 'required',
			'is_summary' => 'required',
			'company_id' => 'required',
			'account_type_id' => 'required',
			'account_subtype_id' => 'required',
        ];
    }
}
