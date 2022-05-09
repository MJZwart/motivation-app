<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'is_public' => 'boolean|required',
            'requires_approval' => 'boolean|required',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'You have not entered a title.',
            'name.*' => 'You have not entered a valid title',
            'description.required' => 'You have not entered a description.',
            'description.*' => 'You have not entered a valid description.',
            'is_public.*' => 'You have not selected the publicity of your group.',
        ];
    }
}
