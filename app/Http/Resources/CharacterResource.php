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
            'name' => $this->name,
            'level' => $this->level,
            'strength' => $this->strength,
            'agility' => $this->agility,
            'endurance' => $this->endurance,
            'intelligence' => $this->intelligence,
            'charisma' => $this->charisma,
            'experience' => $this->experience,
        ];
    }
}
