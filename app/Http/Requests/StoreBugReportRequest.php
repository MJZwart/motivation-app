<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidBugType;

class StoreBugReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'page' => 'required|string|max:255',
            'type' => ['required','string', new ValidBugType()],
            'severity' => 'required|integer|min:1|max:5',
            'image_link' => 'nullable|string',
            'comment' => 'required|string',
        ];
    }

    public function messages(){
        return [
            'title.required' => 'You have not entered a title.',
            'page.required' => 'You have not entered a page.',
            'type.required' => 'You have not selected a type.',
            'severity.*' => 'You have not selected a valid severity.',
            'comment.required' => 'You have not entered a comment.',
        ];
    }
}
