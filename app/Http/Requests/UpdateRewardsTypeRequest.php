<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidRewardType;
use Illuminate\Validation\Rule;

class UpdateRewardsTypeRequest extends FormRequest
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
            'keepOldInstance' => [Rule::requiredIf($this->rewards != 'NONE'), 'nullable'],
            'new_object_name' => [Rule::requiredIf($this->keepOldInstance == 'NEW' && $this->rewards != 'NONE'), 'nullable', 'string', 'max:255'],
        ];
    }

    public function messages() {
        return [
            'keepOldInstance.required' => 'Please select whether to activate an existing instance or create a new one.',
            'new_object_name.max' => 'The name is too long.',
            'new_object_name.*' => 'Please enter a name for this new instance.',
            'rewards.required' => 'Please pick a rewards type.',
        ];
    }
}
