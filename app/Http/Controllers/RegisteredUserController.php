<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ConfirmRegisterRequest;
use App\Models\User;
use App\Models\Character;
use App\Models\Village;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\ExampleTask;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AchievementHandler;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;

class RegisteredUserController extends Controller
{
    /**
     * Creates a new user with a hashed password. Returns confirmation.
     */
    public function store(RegisterUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);
        ActionTrackingHandler::handleAction($request, 'STORE_USER', 'Deleting ' . $request['rewardType'] . ' ' . $request['id']);
        $successMessage = __('messages.user.created');
        return ResponseWrapper::successResponse($successMessage);
    }

    /**
     * Sets additional new-user settings:
     * * The reward type, with a new instance of this reward if applicable
     * * Optionally chosen example tasks
     */
    public function confirmRegister(ConfirmRegisterRequest $request): JsonResponse
    {
        $request->validated();
        /** @var User */
        $user = Auth::user();
        $user->rewards = $request['rewardsType'];
        switch ($request['rewardsType']) {
            case 'NONE':
                $user->show_reward = false;
                break;
            case 'CHARACTER':
                Character::create(
                    [
                        'name' => $request['reward_object_name'],
                        'user_id' => $user->id
                    ]
                );
                break;
            case 'VILLAGE':
                Village::create(
                    [
                        'name' => $request['reward_object_name'],
                        'user_id' => $user->id
                    ]
                );
                break;
        }
        $taskList = TaskList::create(
            [
                'name' => 'Tasks',
                'user_id' => $user->id
            ]
        );
        if (!!$request['tasks']) {
            $this->addExampleTasks($request['tasks'], $user->id, $taskList->id);
            AchievementHandler::checkForAchievement('TASKS_MADE', $user);
        }
        $user->first_login = false;
        $user->save();
        ActionTrackingHandler::handleAction($request, 'UPDATE_USER', 'User finishes first login');
        return ResponseWrapper::successResponse(__('messages.user.setup'), ['user' => new UserResource(Auth::user())]);
    }

    /**
     * Copies the example tasks from the database and adds a new instance of them to the user
     *
     * @param Array $tasks
     * @param Integer $userId
     * @param Integer $taskListId
     */
    private function addExampleTasks($tasks, $userId, $taskListId)
    {
        for ($i = 0; $i < count($tasks); $i++) {
            $task = ExampleTask::find($tasks[$i]);
            Task::create(
                [
                    'name' => $task->name,
                    'description' => $task->description,
                    'difficulty' => $task->difficulty,
                    'type' => $task->type,
                    'repeatable' => $task->repeatable,
                    'user_id' => $userId,
                    'task_list_id' => $taskListId
                ]
            );
        }
    }
}
