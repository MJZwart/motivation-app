<?php

namespace App\Http\Resources;

use App\Models\GroupRole;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $groupUser = $this->findLoggedUser();
        $rank = null;
        if ($groupUser) $rank = GroupRole::find($groupUser->pivot->rank);
        return [
            'id' => $this->id,
            'time_created' => $this->created_at,
            'time_updated' => $this->updated_at,
            'name' => $this->name,
            'description' => $this->description,
            'is_public' => (bool) $this->is_public,
            'require_application' => (bool) $this->require_application,
            'members' => GroupUserResource::collection($this->users),
            'admin' => new StrippedUserResource($this->getAdmin()),
            'rank' => $rank ? new GroupRoleResource($rank) : null,
            'joined' => $groupUser ? Carbon::create($groupUser->pivot->joined) : null,
            'has_application' => $this->require_application ? $this->hasUserApplied() : false,
            'invites' => $rank && $rank->can_manage_members ? $this->invitesAsId() : null,
        ];
    }
}
