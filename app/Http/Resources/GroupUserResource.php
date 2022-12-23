<?php

namespace App\Http\Resources;

use App\Models\GroupRole;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupUserResource extends JsonResource
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
            'user_id' => $this->user_id,
            'username' => $this->user->username,
            'rank' => new GroupRoleResource(GroupRole::find($this->rank)),
            'joined' => Carbon::create($this->joined),
        ];
    }
}
