<?php

namespace App\Http\Resources;

use App\Models\ExperiencePoint;
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
        $maxLevel = ExperiencePoint::max('level');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'level_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->level, $maxLevel),
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
            'a_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->economy, $maxLevel),
            'b_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->labour, $maxLevel),
            'c_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->craft, $maxLevel),
            'd_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->art, $maxLevel),
            'e_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->community, $maxLevel),
            'rewardType' => 'VILLAGE',
            'active' => !!$this->active,
        ];
    }
}
