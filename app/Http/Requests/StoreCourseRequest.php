<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            "name"          =>  "bail|required|alpha|min:2",
            "description"   =>  "bail|min:2",
            "teacher"       =>  "bail|required|alpha|min:2",
            "enabled"       =>  "bail|in:0,1",
            "institution"   =>  "bail|required|exists:institutions,id"
        ];
    }
}
