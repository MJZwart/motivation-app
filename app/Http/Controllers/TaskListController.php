<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use App\Http\Resources\TaskListResource;
use Illuminate\Support\Facades\Auth;

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
        return TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());
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
