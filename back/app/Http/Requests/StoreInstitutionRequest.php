<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstitutionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:4|max:50'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'institution name is required',
            'name.min' => 'institution name is too short',
            'name.max' => 'institution name is too long'
        ];
    }
}
