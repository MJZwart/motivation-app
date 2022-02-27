<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\Groups_Users;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\DeleteGroupRequest;
use App\Http\Requests\JoinGroupRequest;
use App\Http\Requests\LeaveGroupRequest;
use App\Http\Resources\GroupResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class GroupsController extends Controller
{
    public function show($argument){
        if ($argument == 'all') {
            $allGroups = Group::where('is_public', 1)->get();
            foreach ($allGroups as $group){
                $group->members = $group->users->map->only('id', 'username', 'is_admin')->map(function ($member) use ($group) {
                    $member['is_admin'] = $group->isAdmin(User::find($member['id']));
                    return $member;
                });
            }
            return GroupResource::collection($allGroups);
        }
        if ($argument == 'my') {
            $myGroups = Auth::user()->groups;
            foreach ($myGroups as $group){
                $group->members = $group->users->map->only('id', 'username', 'is_admin')->map(function ($member) use ($group) {
                    $member['is_admin'] = $group->isAdmin(User::find($member['id']));
                    return $member;
                });
            }
            return GroupResource::collection($myGroups);
        }
        return new JsonResponse(['message' => ['success' => ["Only 'all' and 'my' are permitted."]]], Response::HTTP_FORBIDDEN);
    }

    public function store(CreateGroupRequest $request): JsonResponse{
        $validated = $request->validated();

        $group = Group::create($validated);
        $group->users()->attach(Auth::user()->id, ['is_admin' => true]);
        
        return new JsonResponse(['message' => ['success' => ["Your group \"{$validated['name']}\" has been created."]]], Response::HTTP_OK);
    }

    public function destroy($id): JsonResponse{
        $groupToDelete = Group::find($id);
        if (!$groupToDelete->isAdmin(Auth::user())) {
            return new JsonResponse(['message' => ['success' => ["You are not an admin of the group you are trying to delete."]]], Response::HTTP_OK);};
        $groupToDelete->users()->detach();
        Group::destroy($groupToDelete->id);
        return new JsonResponse(['message' => ['success' => ["Your group \"{$groupToDelete->name}\" has been deleted!"]]], Response::HTTP_OK);
        
    }

    public function join($id): JsonResponse{
        $group = Group::find($id);
        $users = $group->users;
        if (!$users->find(Auth::user())->isEmpty()) {
            return new JsonResponse(['message' => ['success' => ["You are already a member of this group."]]], Response::HTTP_OK);};
        $users->attach(Auth::user()->id);
        return new JsonResponse(['message' => ['success' => ["You successfully joined the group \"{$group->name}\"."]]], Response::HTTP_OK);
    }

    public function leave($id): JsonResponse{
        $group = Group::find($id);
        $users = $group->users;
        if (Auth::user()->groups->find($id)->isEmpty) {
            return new JsonResponse(['message' => ['success' => ["You are not a member of the group you are trying to leave."]]], Response::HTTP_OK);};
        if ($users->count() == 1) {
            return new JsonResponse(['message' => ['success' => ["You can't leave a group you are the only member of, please delete instead."]]], Response::HTTP_OK);};
        if ($group->isAdmin(Auth::user())) {
            return new JsonResponse(['message' => ['success' => ["You cannot leave a group where you are an admin."]]], Response::HTTP_OK);};
        $users->detach(Auth::user()->id);
        return new JsonResponse(['message' => ['success' => ["You have successfully left the group \"{$group->name}\"."]]], Response::HTTP_OK);
    }
}
