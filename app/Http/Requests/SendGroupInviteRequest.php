<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendGroupInviteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'group_id' => 'integer|exists:groups,id',
            'user_id' => 'integer|exists:users,id',
        ];
    }
}
