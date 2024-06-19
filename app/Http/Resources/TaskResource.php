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
            'id' => $this->id,
            'difficulty' => $this->difficulty,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'super_task' => $this->super_task_id,
            'tasks' => TaskResource::collection($this->activeSubTasks()),
            'task_list_id' => $this->task_list_id,
            'repeatable' => $this->repeatable,
            'repeatable_reset_days' => $this->resetDays(),
        ];
    }
}
