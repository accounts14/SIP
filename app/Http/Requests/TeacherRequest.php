<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->isMethod('get')) {
			return true;
		} else if ($this->isMethod('post')) {
			return true;
		} else {
			return true;
		}
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('get')) {
			return [];
		}
        return [
            'nik'       => "required|unique:teachers,nik,{$this->id}|max:16",
            'nama'      => 'required|max:200',
            'no_hp'     => 'required|max:20',
            'email'     => 'required|max:150|email',
            'school_id' => 'required',
        ];
    }
}
