<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BugReportResource extends JsonResource
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
            'title' => $this->title,
            'page' => $this->page,
            'type' => $this->type,
            'severity' => $this->severity,
            'user_id' => $this->user_id,
            'image_link' => $this->image_link,
            'comment' => $this->comment,
            'admin_comment' => $this->admin_comment,
            'status' => $this->status,
        ];
    }
}
