<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidTaskType;

class UpdateVillageExpGainRequest extends FormRequest
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
            '*.economy' => 'required|integer',
            '*.labour' => 'required|integer',
            '*.craft' => 'required|integer',
            '*.art' => 'required|integer',
            '*.community' => 'required|integer',
            '*.coins' => 'required|integer',
        ];
    }
}
