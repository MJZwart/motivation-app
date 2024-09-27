<?php

namespace App\Http\Requests;

use App\Rules\ExistingAchievementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAchievementRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trigger_type' => ['required', new ExistingAchievementType()],
            'trigger_amount' => 'required|integer|max:9999999999',
        ];
    }
}
