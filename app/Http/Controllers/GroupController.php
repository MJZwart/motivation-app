<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\GroupRoleHandler;
use App\Helpers\NotificationHandler;
use App\Helpers\ResponseWrapper;
use App\Helpers\TimelineHandler;
use App\Http\Requests\GroupMessageRequest;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupApplication;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupsRequest;
use App\Http\Resources\GroupMessageResource;
use App\Http\Resources\GroupPageResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MyGroupResource;
use App\Models\GroupMessage;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{
    /**
     * See an individual group
     *
     * @param Group $group
     * @return JsonResponse
     */
    public function show(Group $group)
    {
        return new JsonResponse(['group' => new GroupPageResource($group)]);
    }

    /**
     * See an overview of all groups plus all groups you're in
     *
     * @return JsonResponse
     */
    public function dashboard()
    {
        /** @var User */
        $user = Auth::user();
        $myGroups = MyGroupResource::collection($user->groups);
        $allGroups = GroupResource::collection(Group::where('is_public', true)->whereNotIn('id', $user->suspendedGroupIds())->get());
        return new JsonResponse(['groups' => ['my' => $myGroups, 'all' => $allGroups]]);
    }

    /**
     * Create a new group
     *
     * @param StoreGroupRequest $request
     * @return JsonResponse
     */
    public function store(StoreGroupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $userId = Auth::user()->id;
        $group = Group::create($validated);
        GroupRoleHandler::createStandardGroupRoles($group->id);
        $adminRank = GroupRoleHandler::getAdminRank($group->id);
        $group->users()->attach($userId, ['rank' => $adminRank->id]);
        TimelineHandler::addGroupCreationToTimeline($group, $userId);
        ActionTrackingHandler::handleAction($request, 'STORE_GROUP', 'Created group ' . $group->name);

        return ResponseWrapper::successResponse(__('messages.group.created', ['name' => $validated['name']]));
    }

    /**
     * Delete a group
     *
     * @param Request $request
     * @param Group $group
     * @return JsonResponse
     */
    public function destroy(Request $request, Group $group): JsonResponse
    {
        $group->users()->detach();
        GroupApplication::where('group_id', $group->id)->delete();
        $group->delete();
        ActionTrackingHandler::handleAction($request, 'DELETE_GROUP', 'Deleted group ' . $group->name);
        return ResponseWrapper::successResponse(__('messages.group.deleted', ['name' => $group->name]));
    }

    /**
     * Transfer ownership of a group to another user
     *
     * @param Request $request
     * @param Group $group
     * @param GroupUser $groupUser
     * @return JsonResponse
     */
    public function transferOwnership(Request $request, Group $group, GroupUser $groupUser): JsonResponse
    {
        if ($groupUser->user_id === Auth::user()->id) return ResponseWrapper::errorResponse(__('messages.group.transfer.failed'));
        $currentAdmin = $group->getAdmin();
        $currentAdmin->update(['rank' => GroupRoleHandler::getMemberRank($group->id)->id]);
        $groupUser->update(['rank' => GroupRoleHandler::getAdminRank($group->id)->id]);
        NotificationHandler::create(
            $groupUser->user_id,
            __('messages.group.transfer.notification_title'),
            __('messages.group.transfer.notification_text', ['admin' => $currentAdmin->user->username, 'group' => $group->name])
        );
        ActionTrackingHandler::handleAction($request, 'TRANSFER_GROUP', 'Transferred ownership of ' . $group->name . ' to ' .$groupUser->user->username);
        return ResponseWrapper::successResponse(__('messages.group.transfer.success'), ['group' => new GroupPageResource($group->fresh())]);
    }

    /**
     * Edits the group
     *
     * @param Group $group
     * @param UpdateGroupsRequest $request
     * @return JsonResponse
     */
    public function update(Group $group, UpdateGroupsRequest $request)
    {
        $validated = $request->validated();
        $group->update($validated);
        $myGroups = MyGroupResource::collection(Auth::user()->groups);
        ActionTrackingHandler::handleAction($request, 'UPDATE_GROUP', $group->name . ' updated.');
        return ResponseWrapper::successResponse(
            __('messages.group.updated'),
            ['groups' => ['my' => $myGroups, 'current' => new GroupPageResource($group->fresh())]]
        );
    }

    /**
     * Group messages
     */

    /**
     * Gets all messages from a group
     *
     * @param Group $group
     * @return JsonResponse with the group messages in a resource
     */
    public function getMessages(Group $group)
    {
        return GroupMessageResource::collection($group->messages->sortByDesc('created_at'));
    }

    /**
     * Creates a group message
     *
     * @param Group $group
     * @param GroupMessageRequest $request
     * @return JsonResponse with the updated group messages in a resource
     */
    public function storeMessage(Group $group, GroupMessageRequest $request) 
    {
        $validated = $request->validated();

        GroupMessage::create([
            'message' => $validated['message'],
            'group_id' => $group->id,
            'user_id' => Auth::user()->id,
        ]);

        ActionTrackingHandler::handleAction($request, 'GROUP_MESSAGE', 'Created message in group '.$group->name);

        return ResponseWrapper::successResponse(__('messages.group.message.created'), ['messages' => GroupMessageResource::collection($group->fresh()->messages->sortByDesc('created_at'))]);
    }

    /**
     * Deletes the message if the user is authorized to do so
     *
     * @param Group $group
     * @param GroupMessage $groupMessage
     * @return void
     */
    public function deleteMessage(Group $group, GroupMessage $groupMessage, Request $request)
    {
        $groupMessage->delete();
        ActionTrackingHandler::handleAction($request, 'GROUP_MESSAGE', 'Deleted message in group '.$group->name);
        return ResponseWrapper::successResponse(__('messages.group.message.deleted'), ['messages' => GroupMessageResource::collection($group->fresh()->messages->sortByDesc('created_at'))]);
    }
}
