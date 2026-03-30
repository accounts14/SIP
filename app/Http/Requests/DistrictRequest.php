<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistrictRequest extends FormRequest
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
    $districtId = $this->route('district');
    return [
        'name'          => 'required|string|unique:district,name,'.$districtId.',id',
        'city_id'       => 'required|numeric|exists:city,id',
        'district_code' => 'nullable|integer',  // ← tambah ini
    ];
}
}
