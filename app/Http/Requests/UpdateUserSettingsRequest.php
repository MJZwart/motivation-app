<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'show_reward' => [Rule::requiredIf($this->rewards != 'NONE'),'boolean'],
            'show_achievements' => 'required|boolean',
            'show_friends' => 'required|boolean',
        ];
    }
}
