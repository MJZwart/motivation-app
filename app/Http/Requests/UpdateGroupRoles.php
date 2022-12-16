<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRoles extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            '*.id' => 'required',
            '*.can_edit' => 'boolean|required',
            '*.can_manage_members' => 'boolean|required',
            '*.can_moderate_messages' => 'boolean|required',
            '*.name' => 'string|required',
        ];
    }
}
