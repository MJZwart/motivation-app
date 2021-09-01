<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ConfirmRegisterRequest;
use App\Models\User;
use App\Models\Character;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\ExampleTask;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function store(RegisterUserRequest $request): JsonResponse{
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        $successMessage = "You have successfully registered. You can now login with your chosen username.";
        return new JsonResponse(['message' => ['success' => [$successMessage]]], Response::HTTP_OK);
    }

    public function confirmRegister(ConfirmRegisterRequest $request): JsonResponse{
        $request->validated();
        $user = Auth::user();
        $user->rewards = $request['rewardsType'];
        switch($request['rewardsType']){
            case 'NONE':
                $user->show_character = false;
                break;
            case 'CHARACTER':
                Character::create(
                    ['name' => $request['character_name'],
                    'user_id' => $user->id]);
                break;
        }
        $taskList = TaskList::create(
            ['name' => 'Tasks',
            'user_id' => $user->id]);
        if(!!$request['tasks']){
            $this->addExampleTasks($request['tasks'], $user->id, $taskList->id);
        }
        $user->first_login = false;
        $user->save();
        return new JsonResponse(['user' => new UserResource(Auth::user())]);
    }

    private function addExampleTasks($tasks, $userId, $taskListId){
        for($i = 0 ; $i < count($tasks) ; $i ++){
            $task = ExampleTask::find($tasks[$i]);
            Task::create(
                ['name' => $task->name,
                'description' => $task->description,
                'difficulty' => $task->difficulty,
                'type' => $task->type,
                'repeatable' => $task->repeatable,
                'user_id' => $userId,
                'task_list_id' => $taskListId]);
        }
    }
}
