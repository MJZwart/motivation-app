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
                $group->members = $group->users->map->only('id', 'username');
            }
            return GroupResource::collection($allGroups);
        }
        if ($argument == 'my') {
            $myGroups = Auth::user()->groups;
            foreach ($myGroups as $group){
                $group->members = $group->users->map->only('id', 'username', 'is_admin');
                foreach ($group->members as &$member) {
                    //dump($member['id'], $member['is_admin']);
                    $members['is_admin'] = $group->isAdmin(User::find($member['id']));
                    //dump($member['is_admin']);
                }
                //dump($group->members);
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
        $isGroupAdmin = $groupToDelete->isAdmin(Auth::user());
        if ($isGroupAdmin) {
            $groupToDelete->users()->detach();
            Group::destroy($groupToDelete->id);
        }
        return new JsonResponse(['message' => ['success' => ["Your group \"{$groupToDelete->name}\" has been deleted!"]]], Response::HTTP_OK);
    }

    public function join(JoinGroupRequest $request): JsonResponse{
        return new JsonResponse(['message' => ['success' => ["Your JoinGroupRequest has been recieved!"]]], Response::HTTP_OK);
    }

    public function leave(LeaveGroupRequest $request): JsonResponse{
        return new JsonResponse(['message' => ['success' => ["Your LeaveGroupRequest has been recieved!"]]], Response::HTTP_OK);
    }
}
