<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('city', 'name')->ignore($this->route('city'))
            ],
            // FIX: 'numeric' bukan 'integer' agar menerima string angka dari JSON
            'province_id' => 'required|numeric|exists:province,id',
            'city_code'   => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Nama kota wajib diisi',
            'name.unique'           => 'Nama kota sudah ada',
            'province_id.required'  => 'Provinsi wajib dipilih',
            'province_id.numeric'   => 'Provinsi tidak valid',
            'province_id.exists'    => 'Provinsi tidak ditemukan',
        ];
    }
}