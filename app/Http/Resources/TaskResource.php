<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'difficulty' => $this->difficulty,
            'type' => $this->type,
            'name' => $this->name,
            'tasks' => TaskResource::collection($this->subTasks),
        ];
    }
}
