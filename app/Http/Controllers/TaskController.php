<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\CharacterResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Helpers\AchievementHandler;
use App\Helpers\ActionTrackingHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request): JsonResponse{
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        Task::create($validated);
        AchievementHandler::checkForAchievement('TASKS_MADE', Auth::user());
        ActionTrackingHandler::handleAction($request, 'STORE_TASK', 'Storing task named: '.$validated['name']);

        $taskLists = TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());

        return new JsonResponse(['message' => ['success' => ["Task successfully created."]], 'data' => $taskLists], Response::HTTP_OK);
    }

    public function update(Task $task, UpdateTaskRequest $request){
        $validated = $request->validated();
        $task->update($validated);
        ActionTrackingHandler::handleAction($request, 'UPDATE_TASK', 'Updated task named: '.$validated['name']);

        $taskLists = TaskListResource::collection(Auth::user()->taskLists);
        
        return new JsonResponse(['message' => ['success' => ["Task successfully updated."]], 'data' => $taskLists], Response::HTTP_OK);
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        if(Auth::user()->id === $task->user_id){
            $task->subTasks()->delete();
            $task->delete();
            ActionTrackingHandler::handleAction($request, 'DELETE_TASK', 'Deleting task named: '.$task->name);

            $taskLists = TaskListResource::collection(Auth::user()->taskLists);
            return new JsonResponse(['message' => ['info' => ["Task deleted."]], 'data' => $taskLists], Response::HTTP_OK);
        } else {
            ActionTrackingHandler::handleAction($request, 'DELETE_TASK', 'Deleting task named: '.$task->name, 'Not authorized');
            return new JsonResponse(['errors' => ['error' => ["You are not authorized to delete this task"]]], Response::HTTP_FORBIDDEN);
        }
    }

    public function complete(Request $request, Task $task){
        /** @var User */
        $user = Auth::user();
        if($user->id === $task->user_id){
            if($task->repeatable != 'NONE'){
                $task->completeRepeatable();
                $this->completeRepeatable($task);
            } else {
                $task->completed = Carbon::now();
                $task->update();
            }
            ActionTrackingHandler::handleAction($request, 'COMPLETE_TASK', 'Completed task named: '.$task->name);

            AchievementHandler::checkForAchievement('TASKS_COMPLETED', $user);
            
            $taskLists = TaskListResource::collection($user->taskLists);
            $activeReward = $user->getActiveRewardObject();
            $returnValue = null;
            if($user->rewards == 'CHARACTER' || $user->rewards == 'VILLAGE') {
                $returnValue = $activeReward->applyReward($task);
                return new JsonResponse(['message' => $returnValue->message, 'data' => $taskLists, 'activeReward' => $returnValue->activeReward], Response::HTTP_OK);
            } else {
                return new JsonResponse(['message' => ['success' => ['Task completed.']], 'data' => $taskLists], Response::HTTP_OK);
            }
        } else {
            ActionTrackingHandler::handleAction($request, 'COMPLETE_TASK', 'Completing task named: '.$task->name, 'Not authorized');
            return new JsonResponse(['errors' => ['error' => ["You are not authorized to complete this task"]]], Response::HTTP_FORBIDDEN);
        }
    }

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
        AchievementHandler::checkForAchievement('REPEATABLE_COMPLETED', Auth::user());
    }
}
