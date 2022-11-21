<?php

namespace App\Http\Resources;

use App\Models\ExperiencePoint;
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
        $maxLevel = ExperiencePoint::max('level');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'level_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->level, $maxLevel),
            'a' => $this->strength,
            'b' => $this->agility,
            'c' => $this->endurance,
            'd' => $this->intelligence,
            'e' => $this->charisma,
            'experience' => $this->experience,
            'coins' => $this->coins,
            'a_exp' => $this->strength_exp,
            'b_exp' => $this->agility_exp,
            'c_exp' => $this->endurance_exp,
            'd_exp' => $this->intelligence_exp,
            'e_exp' => $this->charisma_exp,
            'a_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->strength, $maxLevel),
            'b_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->agility, $maxLevel),
            'c_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->endurance, $maxLevel),
            'd_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->intelligence, $maxLevel),
            'e_exp_needed' => ExperiencePoint::getCurrentOrMaxExp($this->charisma, $maxLevel),
            'rewardType' => 'CHARACTER',
            'active' => !!$this->active,
        ];
    }
}
