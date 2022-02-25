<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Groups_Users;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\DeleteGroupRequest;
use App\Http\Requests\JoinGroupRequest;
use App\Http\Requests\LeaveGroupRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class GroupsController extends Controller
{
    public function show($argument){
        if ($argument == 'all') {
            return new JsonResponse(['message' => ['success' => ["Your request to show ALL groups has been recieved!"]]], Response::HTTP_OK);
        }
        if ($argument == 'my') {
            
            $myGroups = Auth::user()->groups;
            foreach ($myGroups as $group){
                $group->members = Group::find($group->id)->users->map->only('id', 'username');
            }
            //dump($myGroups);
            return new JsonResponse(['message' => ['success' => ["Your request to show MY groups has been recieved!"]], 'data' => $myGroups], Response::HTTP_OK);
        }
        return new JsonResponse(['message' => ['success' => ["Only 'all' and 'my' are permitted."]]], Response::HTTP_FORBIDDEN);
    }

    public function store(CreateGroupRequest $request): JsonResponse{
        $validated = $request->validated();

        $group = Group::create($validated);
        $group->users()->attach(Auth::user()->id, ['is_admin' => true]);
        
        return new JsonResponse(['message' => ['success' => ["Your group \"{$validated['name']}\" has been created."]]], Response::HTTP_OK);
    }

    public function destroy(DeleteGroupRequest $request): JsonResponse{
        return new JsonResponse(['message' => ['success' => ["Your DeleteGroupRequest has been recieved!"]]], Response::HTTP_OK);
    }

    public function join(JoinGroupRequest $request): JsonResponse{
        return new JsonResponse(['message' => ['success' => ["Your JoinGroupRequest has been recieved!"]]], Response::HTTP_OK);
    }

    public function leave(LeaveGroupRequest $request): JsonResponse{
        return new JsonResponse(['message' => ['success' => ["Your LeaveGroupRequest has been recieved!"]]], Response::HTTP_OK);
    }
}
