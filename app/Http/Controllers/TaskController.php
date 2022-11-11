<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Http\Resources\TaskListResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Helpers\AchievementHandler;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use App\Http\Resources\FavouritesResource;
use App\Models\Favourite;
use App\Models\RepeatableTaskCompleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Creates a new task in the user's given task list. Checks for achievement related to making tasks.
     * Tracks action.
     *
     * @param StoreTaskRequest $request
     * @return JsonResponse with message and updated task lists
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        $task = Task::create($validated);
        if ($task->favourite) $this->storeFavourite($task);
        AchievementHandler::checkForAchievement('TASKS_MADE', Auth::user());
        ActionTrackingHandler::handleAction($request, 'STORE_TASK', 'Storing task named: ' . $validated['name']);

        $taskLists = TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());

        return ResponseWrapper::successResponse(__('messages.task.created'), ['taskLists' => $taskLists]);
    }

    /**
     * Creates a favourite from task
     *
     * @param Task $task
     * @return void
     */
    public function storeFavourite(Task $task) 
    {
        Favourite::createFromTask($task);
    }

    /**
     * Edits the given task.
     * Tracks action.
     *
     * @param Task $task
     * @param UpdateTaskRequest $request
     * @return JsonResponse with message and updated task lists.
     */
    public function update(Task $task, UpdateTaskRequest $request)
    {
        $validated = $request->validated();
        $task->update($validated);
        if (!$task->favourite && $validated['favourite']) $this->storeFavourite($task);
        if ($task->favourite && !$validated['favourite']) $this->removeFavouriteFromTask($task);
        ActionTrackingHandler::handleAction($request, 'UPDATE_TASK', 'Updated task named: ' . $validated['name']);

        $taskLists = TaskListResource::collection(Auth::user()->taskLists);

        return ResponseWrapper::successResponse(__('messages.task.updated'), ['taskLists' => $taskLists]);
    }

    /**
     * Removes the linked favourite from a given task, provided this favourite has not since 
     * been updated. When updated, it would need to be removed manually.
     *
     * @param Task $task
     * @return void
     */
    public function removeFavouriteFromTask(Task $task) 
    {
        $favouritesFromTask = Favourite::where('task_id', $task->id)->get();
        foreach ($favouritesFromTask as $fav) {
            if ($fav->updated_at->gt($fav->created_at)) continue;
            $fav->delete();
        }
    }

    /**
     * Deletes the task entirely without giving any rewards. Subtasks are also deleted.
     * Tracks action.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse with message and updated TaskLists if successful.
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        if (Auth::user()->id === $task->user_id) {
            $task->subTasks()->delete();
            $task->delete();
            ActionTrackingHandler::handleAction($request, 'DELETE_TASK', 'Deleting task named: ' . $task->name);

            $taskLists = TaskListResource::collection(Auth::user()->taskLists);
            return ResponseWrapper::successResponse(__('messages.task.deleted'), ['taskLists' => $taskLists]);
        } else {
            ActionTrackingHandler::handleAction($request, 'DELETE_TASK', 'Deleting task named: ' . $task->name, 'Not authorized');
            return ResponseWrapper::forbiddenResponse(__('messages.task.unauthorized'));
        }
    }

    public function getFavourites() {
        return FavouritesResource::collection(Task::where('user_id', Auth::user()->id)->where('favourite', true)->get());
    }

    /**
     * Completes a task. It checks for an achievement, then returns the updated taskLists and reward if applicable.
     * Tracks action.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse with message, taskLists and activeReward if applicable
     */
    public function complete(Request $request, Task $task)
    {
        /** @var User */
        $user = Auth::user();
        if ($user->id === $task->user_id) {
            $this->completeTask($request, $task);
            foreach ($task->activeSubTasks() as $subtask) {
                $this->completeTask($request, $subtask);
            }

            AchievementHandler::checkForAchievement('TASKS_COMPLETED', $user);

            $taskLists = TaskListResource::collection($user->taskLists);
            $activeReward = $user->getActiveRewardObject();
            $returnValue = null;
            if ($user->rewards != 'NONE') {
                $returnValue = $activeReward->applyReward($task);
                //TODO Refactor the response that includes level up notifications
                return ResponseWrapper::successResponse($returnValue->message->success, ['taskLists' => $taskLists, 'activeReward' => $returnValue->activeReward]);
            } else {
                return ResponseWrapper::successResponse(__('messages.task.completed'), ['taskLists' => $taskLists]);
            }
        } else {
            ActionTrackingHandler::handleAction($request, 'COMPLETE_TASK', 'Completing task named: ' . $task->name, 'Not authorized');
            return ResponseWrapper::forbiddenResponse(__('messages.task.unauthorized'));
        }
    }

    /**
     * Completes a task. If the task is repeatable, complete it once and set the 'active' date according to
     * how often it is repeated (eg set the active time for tomorrow midnight if it is daily, or next week
     * if weekly).
     * Tracks action.
     *
     * @param Request $request
     * @param Task $task
     * @return void
     */
    private function completeTask(Request $request, Task $task)
    {
        if ($task->repeatable != 'NONE') {
            $this->completeRepeatable($task);
        } else {
            $task->completed = Carbon::now();
            $task->update();
        }
        ActionTrackingHandler::handleAction($request, 'COMPLETE_TASK', 'Completed task named: ' . $task->name);
    }

    /** A completed repeatable does not get set as completed, but rather gets its 'active' attribute
     * set on the time according to its repeatable (eg set the active time for tomorrow midnight if it 
     * is daily, or next week if weekly). It also checks for achievements related to repeatables completed
     * Adds an entry to the table tracking completed repeatables
     * Tracks action.
     * 
     * @param Task $task
     * @return void
     */
    private function completeRepeatable($task)
    {
        $date = null;
        switch ($task->repeatable) {
            case 'DAILY':
                $date = Carbon::tomorrow();
                break;
            case 'WEEKLY':
                $date = new Carbon('next monday');
                break;
            case 'MONTHLY':
                $date = new Carbon('first day of next month midnight');
                break;
            case 'INFINITE':
                $date = new Carbon();
                break;
        }
        $task->repeatable_active = $date;
        $task->update();
        RepeatableTaskCompleted::create([
            'user_id' => $task->user_id,
            'task_id' => $task->id,
        ]);
        AchievementHandler::checkForAchievement('REPEATABLE_COMPLETED', Auth::user());
    }
}
