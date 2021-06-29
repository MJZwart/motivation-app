<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use App\Http\Resources\TaskListResource;
use App\Http\Requests\StoreTaskListRequest;
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
