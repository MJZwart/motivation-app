<?php

namespace App\Http\Resources;

use App\Models\GroupExperiencePoint;
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
        $groupUser = $this->findLoggedGroupUser();
        $rank = null;
        if ($groupUser) $rank = GroupRole::find($groupUser->rank);
        return [
            'id' => $this->id,
            'time_created' => $this->created_at,
            'time_updated' => $this->updated_at,
            'name' => $this->name,
            'description' => $this->description,
            'is_public' => (bool) $this->is_public,
            'require_application' => (bool) $this->require_application,
            'members' => GroupUserResource::collection($this->groupUsers),
            'admin' => new StrippedUserResource($this->getAdmin()->user),
            'rank' => $rank ? new GroupRoleResource($rank) : null,
            'joined' => $groupUser ? Carbon::create($groupUser->joined) : null,
            'has_application' => $this->require_application ? $this->hasUserApplied() : false,
            'invites' => $rank && $rank->can_manage_members ? $this->invitesAsId() : null,
            'level' => $this->level,
            'experience' => $this->experience,
            'exp_to_next_level' => GroupExperiencePoint::getCurrentOrMaxExp($this->level),
            'your_exp_today' => $groupUser ? $groupUser->expContributionToday() : null,
            'your_exp_total' => $groupUser ? $groupUser->expContributionTotal() : null,
        ];
    }
}
