<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegencyRequest extends FormRequest
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
        $regencyId = $this->route('regency');
        return [
            'name'          => 'required|string|unique:regencies,name,'.$regencyId.',id',
            'province_id'   => 'required|numeric|exists:provinces,id'
        ];
    }
}
