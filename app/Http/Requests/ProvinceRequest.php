<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil id dari route jika ada (untuk update)
        $id = $this->route('province');

        return [
            'prov_name' => [
                'required',
                'string',
                'max:255',
                "unique:province,name," . ($id ?? 'NULL') . ",id",
            ],
            'prov_code' => 'nullable|integer', // bigint di DB, harus integer
        ];
    }

    public function messages(): array
    {
        return [
            'prov_name.required' => 'Nama provinsi wajib diisi.',
            'prov_name.unique'   => 'Nama provinsi sudah terdaftar.',
            'prov_code.integer'  => 'Kode provinsi harus berupa angka.',
        ];
    }
}