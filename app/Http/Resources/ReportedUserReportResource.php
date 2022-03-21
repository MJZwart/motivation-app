<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportedUserReportResource extends JsonResource
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
            'comment' => $this->comment,
            'reported_date' => $this->created_at->toDateTimeString(),
            'reported_by' => $this->reported_by_user_id,
            'conversation' => $this->conversation_id,
            'id' => $this->id,
            'reason' => $this->reason,
        ];
    }
}
