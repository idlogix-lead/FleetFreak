<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
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
			'vehicle_identification_number' => 'required|string',
			'model' => 'required|string',
			'year' => 'required',
			'color' => 'string',
			'license_plate_number' => 'required|string',
			'registration' => 'required|string',
			'ownership' => 'required',
			'fuel_type' => 'required',
			'engine_type' => 'string',
			'transmission_type' => 'required',
        ];
    }
}
