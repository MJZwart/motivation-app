<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidRewardType;
use Illuminate\Validation\Rule;

class UpdateRewardsTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rewards' => ['required', new ValidRewardType()], //TODO, exists:rewards_types,type - make rewards type migration table
            'keepOldInstance' => [Rule::requiredIf($this->rewards != 'NONE'), 'nullable'],
            'newObjectName' => [Rule::requiredIf($this->keepOldInstance == 'NEW' && $this->rewards != 'NONE'), 'nullable', 'string', 'max:255'],
        ];
    }
}
