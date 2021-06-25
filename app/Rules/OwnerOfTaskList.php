<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\TaskList;

class OwnerOfTaskList implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return TaskList::find($value)->user_id === $this->id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This is not your task list.';
    }
}
