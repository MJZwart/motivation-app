<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidBugType;

class StoreBugReportRequest extends FormRequest
{
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
            'diagnostics' => 'sometimes|string',
        ];
    }
}
