<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request): JsonResponse{
        $validated = $request->validated();
        $validated->user_id = Auth::user()->id;

        if($validated->repeatable != 'NONE'){
            $validated->repeatable_active = Carbon::now();
        }

        Task::create($validated);

        $responseMessage = "Task successfully created.";
        return new JsonResponse(['message' => ['message' => [$responseMessage]]], Response::HTTP_OK);
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
