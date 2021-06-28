<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Models\Character;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisteredUserController extends Controller
{
    public function store(RegisterUserRequest $request): JsonResponse{
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        Character::create(
            ['name' => 'Generic McCharacter',
            'user_id' => $user->id]);
        $taskList = TaskList::create(
            ['name' => 'Tasks',
            'user_id' => $user->id]);
        $task1 = Task::create(
            ['name' => 'Create your first task',
            'user_id' => $user->id,
            'task_list_id' => $taskList->id]);
        $task2 = Task::create(
            ['name' => 'Finish these two tasks',
            'user_id' => $user->id,
            'super_task_id' => $task1->id,
            'task_list_id' => $taskList->id]);
        $successMessage = "You have successfully registered. You can now login with your chosen username.";
        return new JsonResponse(['message' => ['success' => [$successMessage]]], Response::HTTP_OK);
    }
}
