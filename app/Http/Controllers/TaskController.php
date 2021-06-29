<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Http\Resources\TaskListResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request): JsonResponse{
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        if($validated['repeatable'] != 'NONE'){
            $validated['repeatable_active'] = Carbon::now();
        }

        Task::create($validated);

        $taskLists = TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());

        return new JsonResponse(['message' => ['message' => ["Task successfully created."]], 'data' => $taskLists], Response::HTTP_OK);
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
    public function update(Task $task, UpdateTaskRequest $request){
        $validated = $request->validated();
        $task->update($validated);

        $taskLists = TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());
        
        return new JsonResponse(['message' => ['message' => ["Task successfully updated."]], 'data' => $taskLists], Response::HTTP_OK);
    }

    public function destroy(Task $task)
    {
        // #32
    }

    public function complete(Task $task){
        // #29
    }
}
