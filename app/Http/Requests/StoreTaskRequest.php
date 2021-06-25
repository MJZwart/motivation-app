<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTaskRequest extends FormRequest
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
            'task_list_id' => ['required', 'integer', 'exists:task_lists,id', new OwnerOf(Auth::user()->id)],
            'difficulty' => 'required|integer|max:5',
            'type' => 'required|integer|max:10',
            'name' => 'required|string|max:255',
            'name' => 'string',
            'super_task_id' => ['integer', 'exists:tasks,id', new OwnerOf(Auth::user()->id)],
            'repeatable' => 'required|string', //TODO: add to database as enum, so only takes one update? Or make new rule to limit the options
        ];
    }
}
