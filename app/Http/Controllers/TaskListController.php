<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use App\Models\TaskList;
use App\Models\Task;
use App\Http\Resources\TaskListResource;
use App\Http\Requests\StoreTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class TaskListController extends Controller
{
    /**
     * Create a new task list with the user given name
     * Returns the updated list of task lists
     */
    public function store(StoreTaskListRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        TaskList::create($validated);
        ActionTrackingHandler::handleAction($request, 'STORE_TASK_LIST', 'Storing tasklist named: ' . $validated['name']);

        $taskLists = TaskListResource::collection(Auth::user()->taskLists);
        return ResponseWrapper::successResponse('Task list successfully created.', $taskLists);
    }

    /**
     * Updates a given task list
     * Param TaskList automatically fetched by given ID
     * Returns updated list of task lists
     */
    public function update(TaskList $tasklist, UpdateTaskListRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $tasklist->update($validated);
        ActionTrackingHandler::handleAction($request, 'UPDATING_TASK_LIST', 'Updating tasklist named: ' . $validated['name']);

        $taskLists = TaskListResource::collection(Auth::user()->taskLists);
        return ResponseWrapper::successResponse("Task list updated.", $taskLists);
    }

    /**
     * Destroys a given task list by Id parameter
     * Returns the updated list of task lists
     */
    public function destroy(Request $request, TaskList $tasklist): JsonResponse
    {
        if (Auth::user()->id === $tasklist->user_id) {
            $tasklist->tasks()->delete();
            $tasklist->delete();
            ActionTrackingHandler::handleAction($request, 'DELETE_TASK_LIST', 'Deleting tasklist named: ' . $tasklist->name);

            $taskLists = TaskListResource::collection(Auth::user()->taskLists);
            return ResponseWrapper::successResponse("Task list deleted.", $taskLists);
        } else {
            ActionTrackingHandler::handleAction($request, 'DELETE_TASK_LIST', 'Deleting tasklist named: ' . $tasklist->name, 'Not authorized');
            return ResponseWrapper::forbiddenResponse("You are not authorized to delete this task list");
        }
    }

    /**
     * Merges tasks from a deleted task list into an active task list
     * Params: Task list ID to be merged into, and tasks in the request object
     */
    public function mergeTasks(TaskList $tasklist, Request $request)
    {
        foreach ($request->tasks as $task) {
            $taskModel = Task::find($task['id']);
            if (!empty($taskModel->tasks)) {
                foreach ($task->tasks as $subTask) {
                    $subTask->task_list_id = $tasklist->id;
                    $subTask->update();
                }
            }
            $taskModel->task_list_id = $tasklist->id;
            $taskModel->update();
        }
    }
}
