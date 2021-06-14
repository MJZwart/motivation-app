<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    //TODO StoreTaskListRequest
    public function store(Request $request)
    {
        // #21
    }

    public function show(TaskList $taskList)
    {
        // #30
    }

    public function showTaskLists(){
        // #30
    }

    //TODO UpdateTaskListRequest
    public function update(TaskList $taskList)
    {
        // #70
    }

    public function destroy(TaskList $taskList)
    {
        // #72
    }
}
