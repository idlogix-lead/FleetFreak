<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocatorRequest extends FormRequest
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
			'company_id' => 'required',
			'warehouse_id' => 'required',
			'code' => 'required|string',
			'locator_type' => 'string',
			'is_active' => 'required',
			'is_default' => 'required',
			'relative_priority' => 'string',
        ];
    }
}
