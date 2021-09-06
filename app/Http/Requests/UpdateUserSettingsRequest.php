<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidRewardType;

class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rewards' => ['required', new ValidRewardType()], //TODO, exists:rewards_types,type - make rewards type migration table
            'show_character' => [Rule::requiredIf($this->rewards == 'CHARACTER'),'boolean'],
            'show_achievements' => 'required|boolean',
            'show_friends' => 'required|boolean',
        ];
    }
}
