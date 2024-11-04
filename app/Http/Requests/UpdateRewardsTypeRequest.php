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
            'rewards' => ['required', new ValidRewardType()],
            'keepOldInstance' => [Rule::requiredIf($this->rewards != 0), 'nullable'],
            'newVillageName' => [Rule::requiredIf($this->keepOldInstance == 'NEW' && $this->rewards != 0), 'nullable', 'string', 'max:255'],
        ];
    }
}
