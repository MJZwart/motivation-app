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
    public function getOtherTaskLists(int $taskListId)
    {
        return TaskList::where('user_id', Auth::user()->id)->whereNot('id', $taskListId)->select('name', 'id')->get();
    }

    /**
     * Create a new task list with the user given name
     * Returns the updated list of task lists
     */
    public function store(StoreTaskListRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        $taskList = TaskList::create($validated);
        ActionTrackingHandler::registerAction($request, 'STORE_TASK_LIST', 'Storing tasklist named: ' . $validated['name']);

    return ResponseWrapper::successResponse(__('messages.tasklist.created'), ['data' => $taskList]);
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
        ActionTrackingHandler::registerAction($request, 'UPDATING_TASK_LIST', 'Updating tasklist named: ' . $validated['name']);

        return ResponseWrapper::successResponse(__('messages.tasklist.updated'), ['data' => $tasklist->fresh()]);
    }

    /**
     * Destroys a given task list by Id parameter
     * Returns the updated list of task lists
     */
    public function destroy(Request $request, TaskList $tasklist): JsonResponse
    {
        $tasklist->tasks()->delete();
        $tasklist->delete();
        ActionTrackingHandler::registerAction($request, 'DELETE_TASK_LIST', 'Deleting tasklist named: ' . $tasklist->name);

        return ResponseWrapper::successResponse(__('messages.tasklist.deleted'));
    }

    /**
     * Merges tasks from a deleted task list into an active task list
     * Params: Task list ID to be merged into, and tasks in the request object
     */
    public function mergeTasks(TaskList $fromTasklist, TaskList $toTasklist)
    {
        Task::where('task_list_id', $fromTasklist->id)->update(['task_list_id' => $toTasklist->id]);
    }
}
