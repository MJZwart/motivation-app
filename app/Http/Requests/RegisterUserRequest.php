<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisterUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|unique:users|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => ['required', 'confirmed', Rules\Password::min(8), 'max:255'],
            'agree_to_tos' => 'required|boolean|accepted',
            'language' => ['required', 'string', Rule::in(['en', 'nl'])],
        ];
    }
}
