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
use App\Helpers\RewardHandler;
use App\Http\Requests\StoreTemplateRequest;
use App\Http\Resources\TemplatesResource;
use App\Models\Template;
use App\Models\RepeatableTaskCompleted;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        if ($task->repeatable === 'WEEKLY_MULTIPLE') {
            $resetDays = [];
            foreach ($validated['repeatable_reset_days'] as $resetDay) {
                array_push($resetDays, [
                    'task_id' => $task->id,
                    'day' => $resetDay,
                ]);
            }
            DB::table('tasks_reset_days')->insert($resetDays);
        }

        AchievementHandler::checkForAchievement('TASKS_MADE', Auth::user());
        ActionTrackingHandler::registerAction($request, 'STORE_TASK', 'Storing task named: ' . $validated['name']);

        $taskLists = TaskListResource::collection(TaskList::where('user_id', Auth::user()->id)->get());

        return ResponseWrapper::successResponse(__('messages.task.created'), ['taskLists' => $taskLists]);
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
        ActionTrackingHandler::registerAction($request, 'UPDATE_TASK', 'Updated task named: ' . $validated['name']);

        $taskLists = TaskListResource::collection(Auth::user()->taskLists);

        return ResponseWrapper::successResponse(__('messages.task.updated'), ['taskLists' => $taskLists]);
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
        $task->subTasks()->delete();
        $task->delete();
        ActionTrackingHandler::registerAction($request, 'DELETE_TASK', 'Deleting task named: ' . $task->name);

        $taskLists = TaskListResource::collection(Auth::user()->taskLists);
        return ResponseWrapper::successResponse(__('messages.task.deleted'), ['taskLists' => $taskLists]);
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
        $this->completeTask($request, $task);
        foreach ($task->activeSubTasks() as $subtask) {
            $this->completeTask($request, $subtask);
        }

        AchievementHandler::checkForAchievement('TASKS_COMPLETED', $user);

        $taskLists = TaskListResource::collection($user->taskLists);
        $returnValue = null;
        if ($user->rewards != 'NONE') {
            $returnValue = RewardHandler::handleTaskRewards($task, $user);
            return new JsonResponse(['messageObject' => $returnValue->message, 'data' => ['taskLists' => $taskLists, 'activeReward' => $returnValue->activeReward]]);
        } else {
            return ResponseWrapper::successResponse(__('messages.task.completed'), ['taskLists' => $taskLists]);
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
        ActionTrackingHandler::registerAction($request, 'COMPLETE_TASK', 'Completed task named: ' . $task->name);
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
                $date = $this->getNextSpecifiedDay($task->repeatable_reset_day);
                break;
            case 'MONTHLY':
                $date = new Carbon('first day of next month midnight');
                break;
            case 'WEEKLY_MULTIPLE':
                $date = $this->getNearestResetDay($task);
                break;
            case 'INFINITE':
                $date = Carbon::now();
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

    private function getNearestResetDay(Task $task)
    {
        $resetDays = $task->resetDays();
        $now = Carbon::today();
        $dayToday = $now->dayOfWeekIso;
        $sortedResetDays = $resetDays->sort();
        $daysAfterToday = $sortedResetDays->filter(function ($value, $dayToday) {
            $value > $dayToday;
        });
        if (count($daysAfterToday) === 0) {
            $amount = 7 - ($dayToday - $sortedResetDays[0]);
            return $now->addDays($amount);
        } else {
            return $now->addDays($daysAfterToday[0] - $dayToday);
        }
    }

    private function getNextSpecifiedDay(int $dayOfTheWeek)
    {
        $now = Carbon::today();
        $dayToday = $now->dayOfWeekIso;
        if ($dayOfTheWeek > $dayToday) {
            return $now->addDays($dayOfTheWeek - $dayToday);
        } else {
            $amount = 7 - ($dayToday - $dayOfTheWeek);
            return $now->addDays($amount);
        }
    }

    /**
     * Fetches all templates by user
     *
     * @return TemplateResourceCollection with all user templates
     */
    public function getTemplates()
    {
        return TemplatesResource::collection(Template::where('user_id', Auth::user()->id)->get());
    }

    /**
     * Creates a template from task
     *
     * @param Task $task
     * @return JsonResponse with success message and all user templates
     */
    public function storeTemplate(StoreTemplateRequest $request)
    {
        /** @var User */
        $userId = Auth::user()->id;

        $validated = $request->validated();
        $validated['user_id'] = $userId;
        Template::create($validated);

        return ResponseWrapper::successResponse(
            __('messages.task.template.created'),
            TemplatesResource::collection(Template::where('user_id', $userId)->get())
        );
    }

    /**
     * Updates the template with the given request and returns a collection of updated templates
     *
     * @param StoreTemplateRequest $request
     * @param Template $template
     * @return JsonResponse with success message and all user templates
     */
    public function updateTemplate(StoreTemplateRequest $request, Template $template)
    {
        $validated = $request->validated();
        $template->update($validated);

        return ResponseWrapper::successResponse(
            __('messages.task.template.updated'),
            TemplatesResource::collection(Template::where('user_id', Auth::user()->id)->get())
        );
    }

    /**
     * Deletes the template
     *
     * @param Template $template
     * @return JsonResponse with success message and all user templates
     */
    public function deleteTemplate(Template $template)
    {
        $template->delete();

        return ResponseWrapper::successResponse(
            __('messages.task.template.deleted'),
            TemplatesResource::collection(Template::where('user_id', Auth::user()->id)->get())
        );
    }
}
