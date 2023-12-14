<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubdistrictRequest extends FormRequest
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
        $subdistrictId = $this->route('subdistrict');
        return [
            'name'          => 'required|string|unique:subdistricts,name,'.$subdistrictId.',id',
            'district_id'   => 'required|numeric|exists:districts,id'
        ];
    }
}
