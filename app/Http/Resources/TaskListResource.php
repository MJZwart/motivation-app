<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TaskResource;
use Carbon\Carbon;

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
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'tasks' => TaskResource::collection($this->activeTasks()),
                // $this->tasks->filter(function ($value, $key) {
                // return $value->super_task_id == null 
                //     && $value->completed == false
                //     && Carbon::parse($value->repeatable_active) < Carbon::now();
            //})),
        ];
    }
}
