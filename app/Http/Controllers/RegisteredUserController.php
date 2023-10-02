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
use Illuminate\Support\Facades\Auth;
use App\Helpers\AchievementHandler;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use App\Rules\ValidRewardType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\RandomStringHelper;
use Illuminate\Support\Facades\Hash;

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
        ActionTrackingHandler::handleAction($request, 'STORE_USER', 'Registering user ' . $request['username']);
        return $this->loginUser($request, ['username' => $request['username'], 'password' => $request['password']]);
    }

    /**
     * Authenticates the user after registering
     */
    private function loginUser($request, $credentials): JsonResponse
    {
        if (Auth::attempt($credentials)) {
            /** @var User */
            $user = Auth::user();
            $request->session()->regenerate();
            ActionTrackingHandler::handleAction($request, 'LOGIN', 'User logged in');
            $user->last_login = Carbon::now();
            $user->save();
            return ResponseWrapper::successResponse(__('messages.user.created'), ['user' => new UserResource($user)]);
        }
        return ResponseWrapper::errorResponse(__('auth.failed'));
    }

    /**
     * Sets additional new-user settings:
     * - The reward type, with a new instance of this reward if applicable
     * - Optionally chosen example tasks
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
     * Creates a guest account with one default task + list and chosen reward with random name
     */
    public function storeGuestAccount(Request $request): JsonResponse
    {
        $validated = $request->validate(['reward' => [new ValidRewardType(), 'required']]);

        $loginToken = Str::random(32);
        $username = $this->generateRandomUsername();

        $guestUser = [
            ...$validated,
            'guest' => true,
            'login_token' => Hash::make($loginToken),
            'username' => $username,
            'password' => Hash::make(Str::random(32)),
            'rewards' => $validated['reward'],
            'first_login' => false,
        ];

        $user = User::create($guestUser);

        $this->createRewardForGuest($validated['reward'], $user->id);
        $this->createStarterTasks($user->id);

        ActionTrackingHandler::handleAction($request, 'STORE_USER', 'Registering guest user ' . $username);
        $request['username'] = $username;
        $request['loginToken'] = $loginToken;
        return $this->loginGuestAccount($request);
    }

    /**
     * Authenticates the guest account after having been created and returns the token to login again
     */
    private function loginGuestAccount(Request $request): JsonResponse
    {
        $user = User::where('guest', true)->where('username', $request['username'])->first();
        if ($user->exists() && Hash::check($request['loginToken'], $user->login_token)) {
            $request->session()->regenerate();
            ActionTrackingHandler::handleAction($request, 'LOGIN', 'Guest user logged in');
            $user->last_login = Carbon::now();
            $user->save();
            Auth::login($user);
            $loginTokenEncoded = base64_encode('{"loginToken": "' . $request['loginToken'] . '", "username": "' . $user->username . '"}');
            return ResponseWrapper::successResponse(__('messages.user.guest.created'), ['user' => new UserResource($user), 'loginToken' => $loginTokenEncoded]);
        }
        return ResponseWrapper::errorResponse(__('auth.failed'));
    }

    /**
     * Generates a random username with an adjective + animal + 4 numbers
     * Checks for uniqueness and adds extra numbers of not unique after 5 tries
     */
    private function generateRandomUsername(): string
    {
        $username = RandomStringHelper::getRandomAdjective(true) . RandomStringHelper::getRandomAnimal(true);
        $username .= mt_rand(1000, 9999);
        $attempt = 0;
        while (User::where('username', $username)->exists()) {
            $attempt++;
            if ($attempt > 5) {
                $username .= mt_rand(10, 99);
            } else {
                $username = RandomStringHelper::getRandomAdjective(true) . RandomStringHelper::getRandomAnimal(true);
            }
        }
        return $username;
    }

    /**
     * Creates a character or village if chosen with a random name (adjective + animal)
     */
    private function createRewardForGuest(string $type, int $userId): void
    {
        if ($type === 'NONE') return;
        if ($type === 'CHARACTER') {
            Character::create([
                'name' => RandomStringHelper::getRandomAdjective(true) . RandomStringHelper::getRandomAnimal(true),
                'user_id' => $userId,
            ]);
        }
        if ($type === 'VILLAGE') {
            VILLAGE::create([
                'name' => RandomStringHelper::getRandomAdjective(true) . RandomStringHelper::getRandomAnimal(true),
                'user_id' => $userId,
            ]);
        }
    }

    /**
     * Creates a basic starter task and task list
     */
    private function createStarterTasks($userId): void
    {
        $tasklist = TaskList::create([
            'name' => 'Tasks',
            'user_id' => $userId,
        ]);
        Task::create([
            'name' => 'Create your first task',
            'difficulty' => 1,
            'user_id' => $userId,
            'task_list_id' => $tasklist->id,
        ]);
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
