<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
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
        return [];
        // if ($this->isMethod('get') or $this->isMethod('put')) {
		// 	return [];
		// }
        // return [
        //     'images' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
        // ];
    }
}
