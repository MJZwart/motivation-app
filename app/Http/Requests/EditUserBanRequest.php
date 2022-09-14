<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EditUserBanRequest extends FormRequest
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
            'days' => 'required_if:end_ban,false|nullable|integer',
            'end_ban' => 'boolean',
            'ban_edit_comment' => 'required|string',
            'ban_edit_log' => 'required|string',
        ];
    }

    public function messages(){
        return [
            'log.required' => 'A log is required.',
            'comment.required' => 'A comment is required to explain why this ban was changed/lifted.',
            'days.require_id' => 'If you are not lifting the ban, please fill in the amount of days.'
        ];
    }
}
