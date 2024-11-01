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
            'economy' => $this->economy,
            'labour' => $this->labour,
            'craft' => $this->craft,
            'art' => $this->art,
            'community' => $this->community,
            'experience' => $this->experience,
            'coins' => $this->coins,
            'economy_exp' => $this->economy_exp,
            'labour_exp' => $this->labour_exp,
            'craft_exp' => $this->craft_exp,
            'art_exp' => $this->art_exp,
            'community_exp' => $this->community_exp,
            'economy_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->economy, $maxLevel),
            'labour_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->labour, $maxLevel),
            'craft_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->craft, $maxLevel),
            'art_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->art, $maxLevel),
            'community_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->community, $maxLevel),
            'active' => !!$this->active,
        ];
    }
}
