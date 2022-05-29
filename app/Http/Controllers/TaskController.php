<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Http\Resources\TaskListResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Helpers\AchievementHandler;
use App\Helpers\ActionTrackingHandler;
use App\Models\RepeatableTaskCompleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
    public function store(StoreTaskRequest $request): JsonResponse{
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        Task::create($validated);
        AchievementHandler::checkForAchievement('TASKS_MADE', Auth::user());
        ActionTrackingHandler::handleAction($request, 'STORE_TASK', 'Storing task named: '.$validated['name']);

        $taskLists = TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());

        return new JsonResponse(['message' => ['success' => "Task successfully created."], 'data' => $taskLists], Response::HTTP_OK);
    }

    /**
     * Edits the given task.
     * Tracks action.
     *
     * @param Task $task
     * @param UpdateTaskRequest $request
     * @return JsonResponse with message and updated task lists.
     */
    public function update(Task $task, UpdateTaskRequest $request){
        $validated = $request->validated();
        $task->update($validated);
        ActionTrackingHandler::handleAction($request, 'UPDATE_TASK', 'Updated task named: '.$validated['name']);

        $taskLists = TaskListResource::collection(Auth::user()->taskLists);
        
        return new JsonResponse(['message' => ['success' => "Task successfully updated."], 'data' => $taskLists], Response::HTTP_OK);
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
        if(Auth::user()->id === $task->user_id){
            $task->subTasks()->delete();
            $task->delete();
            ActionTrackingHandler::handleAction($request, 'DELETE_TASK', 'Deleting task named: '.$task->name);

            $taskLists = TaskListResource::collection(Auth::user()->taskLists);
            return new JsonResponse(['message' => ['info' => "Task deleted."], 'data' => $taskLists], Response::HTTP_OK);
        } else {
            ActionTrackingHandler::handleAction($request, 'DELETE_TASK', 'Deleting task named: '.$task->name, 'Not authorized');
            return new JsonResponse(['message' => "You are not authorized to delete this task"], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Completes a task. It checks for an achievement, then returns the updated taskLists and reward if applicable.
     * Tracks action.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse with message, taskLists and activeReward if applicable
     */
    public function complete(Request $request, Task $task){
        /** @var User */
        $user = Auth::user();
        if($user->id === $task->user_id){
            $this->completeTask($request, $task);
            foreach ($task->activeSubTasks() as $subtask) {
                $this->completeTask($request, $subtask);
            }

            AchievementHandler::checkForAchievement('TASKS_COMPLETED', $user);
            
            $taskLists = TaskListResource::collection($user->taskLists);
            $activeReward = $user->getActiveRewardObject();
            $returnValue = null;
            if($user->rewards != 'NONE') {
                $returnValue = $activeReward->applyReward($task);
                return new JsonResponse(['message' => $returnValue->message, 'data' => $taskLists, 'activeReward' => $returnValue->activeReward], Response::HTTP_OK);
            } else {
                return new JsonResponse(['message' => ['success' => 'Task completed.'], 'data' => $taskLists], Response::HTTP_OK);
            }
        } else {
            ActionTrackingHandler::handleAction($request, 'COMPLETE_TASK', 'Completing task named: '.$task->name, 'Not authorized');
            return new JsonResponse(['message' => "You are not authorized to complete this task"], Response::HTTP_FORBIDDEN);
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
    private function completeTask(Request $request, Task $task) {
        if($task->repeatable != 'NONE'){
            $this->completeRepeatable($task);
        } else {
            $task->completed = Carbon::now();
            $task->update();
        }
        ActionTrackingHandler::handleAction($request, 'COMPLETE_TASK', 'Completed task named: '.$task->name);
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
    private function completeRepeatable($task){
        $date = null;
        switch($task->repeatable){
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
