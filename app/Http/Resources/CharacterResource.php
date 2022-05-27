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
            'level_exp_needed' => ExperiencePoint::where('level', $this->level)->orWhere('level', $maxLevel)->first()->experience_points,
            'a' => $this->strength,
            'b' => $this->agility,
            'c' => $this->endurance,
            'd' => $this->intelligence,
            'e' => $this->charisma,
            'experience' => $this->experience,
            'a_exp' => $this->strength_exp,
            'b_exp' => $this->agility_exp,
            'c_exp' => $this->endurance_exp,
            'd_exp' => $this->intelligence_exp,
            'e_exp' => $this->charisma_exp,
            'a_exp_needed' => ExperiencePoint::where('level', $this->strength)->orWhere('level', $maxLevel)->first()->experience_points,
            'b_exp_needed' => ExperiencePoint::where('level', $this->agility)->orWhere('level', $maxLevel)->first()->experience_points,
            'c_exp_needed' => ExperiencePoint::where('level', $this->endurance)->orWhere('level', $maxLevel)->first()->experience_points,
            'd_exp_needed' => ExperiencePoint::where('level', $this->intelligence)->orWhere('level', $maxLevel)->first()->experience_points,
            'e_exp_needed' => ExperiencePoint::where('level', $this->charisma)->orWhere('level', $maxLevel)->first()->experience_points,
            'rewardType' => 'CHARACTER',
            'active' => !!$this->active,
        ];
    }
}
