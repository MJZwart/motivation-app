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
        if($request['rewardsType'] == 'NONE'){
            //
        } elseif($request['rewardsType'] == 'CHARACTER'){
            Character::create(
                ['name' => $request['character_name'],
                'user_id' => $user->id]);
        }
        $taskList = TaskList::create(
            ['name' => 'Tasks',
            'user_id' => $user->id]);
        if(!!$request['tasks']){
            $exampleTaskArray = $request['tasks'];
            for($i = 0 ; $i < count($exampleTaskArray) ; $i ++){
                $task = ExampleTask::find($exampleTaskArray[$i]);
                Task::create(
                    ['name' => $task->name,
                    'description' => $task->description,
                    'difficulty' => $task->difficulty,
                    'type' => $task->type,
                    'repeatable' => $task->repeatable,
                    'user_id' => $user->id,
                    'task_list_id' => $taskList->id]);
            }
        }
        $user->first_login = false;
        $user->save();
        return new JsonResponse(['user' => new UserResource(Auth::user())]);
    }
}
