<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverAssignmentRequest extends FormRequest
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
			'order_detail_id' => 'required',
			'driver_id' => 'required',
			'vehicle_id' => 'required',
        ];
    }
}
