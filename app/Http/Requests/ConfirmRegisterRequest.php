<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\ValidRewardType;

class ConfirmRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reward_object_name' => [Rule::requiredIf($this->rewardsType != 'NONE'), 'string', 'nullable', 'max:255'],
            'rewardsType' => [new ValidRewardType()],
        ];
    }
}
