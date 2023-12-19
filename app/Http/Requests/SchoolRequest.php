<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
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
        return [
            'name'          => 'required',
            'type'          => 'required',
            'accrediation'  => 'nullable',
            'level'         => 'string|required',
            'established'   => 'nullable',
            'npsn'          => 'nullable',
            'headmaster'    => 'nullable',
            'class'         => 'nullable',
            'curriculum'    => 'nullable',
            'student'       => 'nullable',
            'location'      => 'nullable',
            'longitude'     => 'nullable',
            'latitude'      => 'nullable',
            'link_location' => 'nullable',
            'telephone'     => 'nullable',
            'web'           => 'nullable',
            'motto'         => 'nullable',
            'content_array' => 'nullable',
            'school_status' => 'nullable',
            'avatar'        => 'nullable',
            'banner'        => 'nullable',
        ];
    }
}
