<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupUserResource;
use App\Http\Resources\StrippedUserResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
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
            'requires_approval' => (boolean) $this->requies_approbal,
            'members' => GroupUserResource::collection($this->users),
            'admin' => new StrippedUserResource($this->getAdmin()),
        ];
    }
}
