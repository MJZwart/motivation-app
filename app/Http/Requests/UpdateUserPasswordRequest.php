<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use App\Rules\MatchOldPassword;

class UpdateUserPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => ['required', 'string', new MatchOldPassword(), 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::min(8), 'max:255'],
        ];
    }
}
