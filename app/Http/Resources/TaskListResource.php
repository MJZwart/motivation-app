<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TaskResource;

class TaskListResource extends JsonResource
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
            'color' => $this->color,
            'tasks' => TaskResource::collection($this->tasks->filter(function ($value, $key) {
                return $value->super_task_id == null;
            })),
        ];
    }
}
