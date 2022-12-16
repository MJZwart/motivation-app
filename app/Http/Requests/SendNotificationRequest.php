<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
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
            'text' => 'required|string',
            'link' => 'nullable|string',
            'link_text' => 'required_with:link|nullable|string',
        ];
    }
}
