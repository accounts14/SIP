<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCandidateRequest extends FormRequest
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
            'nik' => "required|unique:user_candidates,nik,{$this->id}|max:16",
            'nisn' => 'required|max:50',
            'nama' => 'required|max:100',
            'alamat' => 'required|max:200',
            'tempat_lahir' => 'required|max:100',
            'tanggal_lahir' => 'required',
            'email' => 'required|email|max:150',
            'no_hp' => 'required|max:16',
        ];
    }
}
