<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\Groups_Users;
use App\Http\Requests\StoreGroupRequest;
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
            $allGroups = Group::where('is_public', true)->get();
            foreach ($allGroups as $group){
                $group->members = $group->users->map->only('id', 'username', 'rank')->map(function ($member) use ($group) {
                    $member['rank'] = $group->rankOfMemberById($member['id']);
                    return $member;
                });
            }
            return GroupResource::collection($allGroups);
        }
        if ($argument == 'my') {
            $myGroups = Auth::user()->groups;
            foreach ($myGroups as $group){
                $group->members = $group->users->map->only('id', 'username', 'rank')->map(function ($member) use ($group) {
                    $member['rank'] = $group->rankOfMemberById($member['id']);
                    return $member;
                });
            }
            return GroupResource::collection($myGroups);
        }
        return new JsonResponse(['message' => ['error' => ["Only 'all' and 'my' are permitted."]]], Response::HTTP_FORBIDDEN);
    }

    public function store(StoreGroupRequest $request): JsonResponse{
        $validated = $request->validated();

        $group = Group::create($validated);
        $group->users()->attach(Auth::user()->id, ['rank' => 'admin']);
        
        return new JsonResponse(['message' => ['success' => ["Your group \"{$validated['name']}\" has been created."]]], Response::HTTP_OK);
    }

    public function destroy(Group $group): JsonResponse{
        if (!$group->isAdminById(Auth::user()->id))
            return new JsonResponse(['message' => ['error' => ["You are not an admin of the group you are trying to delete."]]], Response::HTTP_BAD_REQUEST);
        $group->users()->detach();
        $group->delete();
        return new JsonResponse(['message' => ['success' => ["Your group \"{$group->name}\" has been deleted!"]]], Response::HTTP_OK);
        
    }

    public function join(Group $group): JsonResponse{
        $user = Auth::user();
        $users = $group->users();
        //dd($user->id, $group->id, $group->users, $users->find($user) == $user);
        if ($users->find($user)) 
            return new JsonResponse(['message' => ['error' => ["You are already a member of this group."]]], Response::HTTP_BAD_REQUEST);
        $users->attach($user);
        return new JsonResponse(['message' => ['success' => ["You successfully joined the group \"{$group->name}\"."]]], Response::HTTP_OK);
    }

    public function leave(Group $group): JsonResponse{
        $user = Auth::user();
        $users = $group->users();
        if (!$user->groups()->find($group))
            return new JsonResponse(['message' => ['error' => ["You are not a member of the group you are trying to leave."]]], Response::HTTP_BAD_REQUEST);
        if ($users->count() == 1)
            return new JsonResponse(['message' => ['error' => ["You can't leave a group you are the only member of, please delete instead."]]], Response::HTTP_BAD_REQUEST);
        if ($group->isAdminById($user->id))
            return new JsonResponse(['message' => ['error' => ["You cannot leave a group where you are an admin."]]], Response::HTTP_BAD_REQUEST);
        $users->detach($user);
        return new JsonResponse(['message' => ['success' => ["You have successfully left the group \"{$group->name}\"."]]], Response::HTTP_OK);
    }
}
