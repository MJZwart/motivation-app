<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'title' => 'required|string',
            'page' => 'required|string',
            'type' => 'required|string',
            'severity' => 'required|integer|max:5',
            'image_link' => 'nullable|string',
            'comment' => 'required|string',
        ];
    }
}
