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
            'username' => $this->username,
            'rank' => $this->whenPivotLoaded('group_user', function() {
                return new GroupRoleResource(GroupRole::find($this->pivot->rank));
            }),
            'joined' => $this->whenPivotLoaded('group_user', function() {
                return Carbon::create($this->pivot->joined);
            })
        ];
    }
}
