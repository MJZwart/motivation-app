<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|string|max:255',
            'text' => 'required|string',
            'email' => 'required_without:user_id|nullable|email',
            'user_id' => 'nullable|exists:users,id',
            'diagnostics' => 'sometimes|string',
        ];
    }
}
