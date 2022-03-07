<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidTaskType;

class UpdateCharacterExpGainRequest extends FormRequest
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
            '*.id' => 'required|integer',
            '*.task_type' => ['required', new ValidTaskType()],
            '*.level' => 'required|integer',
            '*.strength' => 'required|integer',
            '*.agility' => 'required|integer',
            '*.endurance' => 'required|integer',
            '*.intelligence' => 'required|integer',
            '*.charisma' => 'required|integer',
        ];
    }
    
    public function messages(){
        return [
            '*.*.required' => 'You have not set a value on :attribute',
        ];
    }
}
