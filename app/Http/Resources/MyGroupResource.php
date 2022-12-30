<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MyGroupResource extends JsonResource
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
            'is_public' => (boolean) $this->is_public,
            'require_application' => (boolean) $this->require_application,
            'members' => $this->groupUsers->count(),
            'rank' => $this->loggedUserRank(),
            'joined' => Carbon::create($this->findLoggedGroupUser()->joined),
        ];
    }
}
