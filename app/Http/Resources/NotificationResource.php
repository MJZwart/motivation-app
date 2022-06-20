<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'read' => !!$this->read,
            'created_at' => $this->created_at,
            'title' => $this->title,
            'text' => $this->text,
            'links' => json_decode($this->links),
            'delete_links_on_action' => $this->delete_links_on_action,
        ];
    }
}
