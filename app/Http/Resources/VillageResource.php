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
            'economy' => $this->strength,
            'labour' => $this->agility,
            'craft' => $this->endurance,
            'art' => $this->intelligence,
            'community' => $this->charisma,
            'experience' => $this->experience,
            'economy_exp' => $this->strength_exp,
            'labour_exp' => $this->agility_exp,
            'craft_exp' => $this->endurance_exp,
            'art_exp' => $this->intelligence_exp,
            'community_exp' => $this->charisma_exp,
            'experienceTable' => $this->experienceTable(),
        ];
    }
}
