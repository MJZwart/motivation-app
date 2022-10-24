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
            'admin_comment' => 'nullable|string|max:255',
            'status' => 'required|integer|min:0|max:3',
            'title' => 'required|string',
        ];
    }
}
