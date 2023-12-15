<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $cityId = $this->route('city');
        return [
            'city_name'     => 'required|string|unique:cities,city_name,'.$cityId.',id',
            'prov_id'       => 'required|numeric|exists:provinces,id'
        ];
    }
}
