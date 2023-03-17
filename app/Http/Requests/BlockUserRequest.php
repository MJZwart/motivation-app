<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'userId' => ['required', 'integer', 'exists:users,id'],
            'hideMessages' => ['required', 'boolean'],
        ];
    }
}
