<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use App\Http\Resources\TaskListResource;
use App\Http\Requests\StoreTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TaskListController extends Controller
{
    public function store(StoreTaskListRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        TaskList::create($validated);

        $taskLists = TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());
        return new JsonResponse(['message' => ['message' => ['Task list successfully created.']], 'data' => $taskLists], Response::HTTP_OK);
    }

    public function show(TaskList $taskList)
    {
        // #30
    }

    public function showTaskLists(){
        return TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());
    }

    public function update(TaskList $tasklist, UpdateTaskListRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $tasklist->update($validated);

        $taskLists = TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());
        return new JsonResponse(['message' => ['message' => ["Task list successfully updated."]], 'data' => $taskLists], Response::HTTP_OK);
    }

    public function destroy(TaskList $tasklist): JsonResponse
    {
        if(Auth::user()->id === $tasklist->user_id){
            $tasklist->tasks()->delete();
            $tasklist->delete();

            $taskLists = TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());
            return new JsonResponse(['message' => ['message' => ["Task list successfully deleted."]], 'data' => $taskLists], Response::HTTP_OK);
        } else {
            return new JsonResponse(['errors' => ['error' => ["You are not authorized to delete this task list"]]], Response::HTTP_FORBIDDEN);
        }
    }
}
