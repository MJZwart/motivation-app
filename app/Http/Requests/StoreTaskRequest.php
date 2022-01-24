<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\OwnerOfTaskList;
use App\Rules\OwnerOfTask;
use App\Rules\ValidRepeatable;
use App\Rules\ValidTaskType;

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
            'task_list_id' => ['required', 'integer', 'exists:task_lists,id', new OwnerOfTaskList(Auth::user()->id)],
            'difficulty' => 'required|integer|max:5',
            'type' => ['required', 'string', new ValidTaskType()],
            'name' => 'required|string|max:255',
            'description' => 'string',
            'super_task_id' => ['nullable', 'integer', 'exists:tasks,id', new OwnerOfTask(Auth::user()->id)],
            'repeatable' => ['required', 'string', new ValidRepeatable()], //TODO: add to database as enum, so only takes one update? Or make new rule to limit the options
        ];
    }

    public function messages(){
        return [
            'task_list_id.required' => 'You have not selected a task list.',
            'task_list_id.*' => 'You have not selected a valid task list.',
            'difficulty.max' => 'You have not selected a valid difficulty.',
            'name.max' => 'The name you picked is too long. Use the description to add more information.',
            'super_task_id.*' => 'You have not picked a valid task to make this a sub-task of.',
            'repeatable.*' => 'You have not picked a valid repeatable type.',
        ];
    }
}
