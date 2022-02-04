<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidBugType;

class UpdateBugReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required','string', new ValidBugType()],
            'severity' => 'required|integer|min:1|max:5',
            'admin_comment' => 'nullable|string',
            'status' => 'required|integer|min:0|max:3',
        ];
    }

    public function messages(){
        return [
            'type.required' => 'You have not selected a type.',
            'type.*' => 'You have not selected a valid type',
            'severity.*' => 'You have not selected a severity.',
            'severity.*' => 'You have not selected a valid severity.',
            'status.required' => 'You have not selected a status.',
            'status.*' => 'You have not selected a valid status.',
        ];
    }
}
