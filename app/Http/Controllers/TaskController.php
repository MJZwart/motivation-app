<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //TODO StoreTaskRequest
    public function store(){
        // #19
    }

    /*
    Will likely be changed to account for TaskList
    */
    public function show(Task $task) {
        // #30
    }

    public function showTasks(){
        // #30
    }
    /*

    */

    //TODO UpdateTaskRequest
    public function update(Task $task){
        // #31
    }

    public function destroy(Task $task)
    {
        // #32
    }

    public function complete(Task $task){
        // #29
    }
}
