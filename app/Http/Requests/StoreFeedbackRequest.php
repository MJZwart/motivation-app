<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
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
            'type' => 'required|string',
            'text' => 'required|string',
            'email' => 'required_without:user|nullable|string',
            'user' => 'nullable|exists:users,id',
        ];
    }

    public function messages(){
        return [
            'email.required_without' => 'Please enter an e-mail address so we can contact you if we have further questions or remarks.',
        ];
    }
}
