<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'is_member' => !!$this->findLoggedUser(),
            'rank' => $this->findLoggedUser() ? $this->findLoggedUser()->pivot->rank : null,
            'joined' => $this->findLoggedUser() ? Carbon::create($this->findLoggedUser()->pivot->joined) : null,
            'has_application' => $this->require_application ? $this->hasUserApplied() : false,
            'invites' => Auth::user()->id === $this->getAdmin()->id ? $this->invitesAsId() : null,
        ];
    }
}
