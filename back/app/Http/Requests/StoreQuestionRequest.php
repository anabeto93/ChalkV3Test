<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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
            "type"  =>  "bail|required|in:multiple_choice,short_exact",
            "quiz"  =>  "bail|required|exists:quizzes,id",
            "title" =>  "bail|required|string"
        ];
    }
}
