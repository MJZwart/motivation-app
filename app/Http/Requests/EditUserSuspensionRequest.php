<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EditUserSuspensionRequest extends FormRequest
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
            'days' => 'required_if:end_suspension,false|nullable|integer',
            'end_suspension' => 'boolean',
            'suspension_edit_comment' => 'required|string',
            'suspension_edit_log' => 'required|string',
        ];
    }
}
