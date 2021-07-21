<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
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
            'tasks_completed' => $this->getTotalTasksCompleted(),
            'repeatable_most_completed' => $this->getRepeatableTaskMostCompleted(),
        ];
    }
}
