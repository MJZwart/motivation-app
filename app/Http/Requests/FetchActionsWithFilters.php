<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchActionsWithFilters extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'array|nullable',
            'users' => 'array|nullable',
            'date' => 'array|nullable',

            'type.*' => 'sometimes|string',
            'users.*' => 'sometimes|exists:users,id',
            'date.*' => 'sometimes|date',
        ];
    }
}
