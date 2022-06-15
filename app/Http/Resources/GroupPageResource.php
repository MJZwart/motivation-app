<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'time_created' => $this->created_at->toDateTimeString(),
            'time_updated' => $this->updated_at->toDateTimeString(),
            'name' => $this->name,
            'description' => $this->description,
            'is_public' => (boolean) $this->is_public,
            'require_application' => (boolean) $this->require_application,
            'members' => GroupUserResource::collection($this->users),
            'admin' => new StrippedUserResource($this->getAdmin()),
            'is_member' => !!$this->findLoggedUser(),
            'rank' => $this->findLoggedUser() ? $this->findLoggedUser()->pivot->rank : null,
            'joined' => $this->findLoggedUser() ? $this->findLoggedUser()->pivot->joined : null,
            'has_application' => $this->require_application ? $this->hasUserApplied() : false,
        ];
    }
}
