<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => 'required|string|max:4294967290',
            'recipient_id' => 'required|exists:users,id',
            'conversation_id' => 'nullable|integer',
        ];
    }
}
