<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CharacterResource extends JsonResource
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
            'strength' => $this->strength,
            'agility' => $this->agility,
            'endurance' => $this->endurance,
            'intelligence' => $this->intelligence,
            'charisma' => $this->charisma,
            'experience' => $this->experience,
            'strength_exp' => $this->strength_exp,
            'agility_exp' => $this->agility_exp,
            'endurance_exp' => $this->endurance_exp,
            'intelligence_exp' => $this->intelligence_exp,
            'charisma_exp' => $this->charisma_exp,
            'experienceTable' => $this->experienceTable(),
        ];
    }
}
