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
            'reported_user_id' => 'required|integer|exists:users,id',
            'reported_by_user_id' => 'required|integer|exists:users,id',
            'comment' => 'required_without:conversation_id|string',
            'reason' => 'required|string',
            'conversation_id' => 'required_without:comment|string',
        ];
    }
}
