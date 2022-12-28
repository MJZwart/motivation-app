<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReportedUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment' => 'required|string|nullable',
            'reason' => 'required|string|max:255',
            'conversation_id' => 'sometimes|string',
            'group_id' => 'sometimes|integer',
        ];
    }
}
