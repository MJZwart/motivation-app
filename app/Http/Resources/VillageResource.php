<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VillageResource extends JsonResource
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
            'name' => $this->name,
            'level' => $this->level,
            'a' => $this->economy,
            'b' => $this->labour,
            'c' => $this->craft,
            'd' => $this->art,
            'e' => $this->community,
            'experience' => $this->experience,
            'a_exp' => $this->economy_exp,
            'b_exp' => $this->labour_exp,
            'c_exp' => $this->craft_exp,
            'd_exp' => $this->art_exp,
            'e_exp' => $this->community_exp,
            'rewardType' => 'VILLAGE',
            'experienceTable' => $this->experienceTable(),
        ];
    }
}
