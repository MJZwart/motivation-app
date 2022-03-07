<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReportedUserRequest extends FormRequest
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
            'comment' => 'required_without:conversation_id|string|nullable',
            'reason' => 'required|string',
            'conversation_id' => 'required_without:comment|string',
        ];
    }

    public function messages(){
        return [
            'comment.required_without' => 'Please explain why you are reporting this user.',
            'reason.required' => 'Please select a reason.',
        ];
    }
}
