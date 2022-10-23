<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BanUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => 'required|string',
            'days' => 'required_if:indefinite,false|integer|min:1|nullable',
            'indefinite' => 'required_if:days,<1|boolean',
            'close_reports' => 'required|boolean',
        ];
    }

    public function messages(){
        return [
            'days.required_if' => 'The amount of days is required if not indefinite.',
            'days.min' => 'The amount of days must be more than 1 if not indefinite.',
            'indefinite.required_if' => 'If no days are selected, please check indefinite.',
        ];
    }
}
