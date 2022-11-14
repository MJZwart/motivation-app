<?php

namespace App\Http\Requests;

use App\Rules\ValidTaskType;
use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'difficulty' => 'required|integer|max:5',
            'type' => ['required', 'string', new ValidTaskType()],
        ];
    }
}
