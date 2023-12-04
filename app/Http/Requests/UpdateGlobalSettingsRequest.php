<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGlobalSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            '*.key' => ['string', 'exists:global_settings,key'],
            '*.value' => ['integer', 'required'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.value.integer' => 'The value must be a number',
            '*.value.required' => 'This field is required',
        ];
    }
}
