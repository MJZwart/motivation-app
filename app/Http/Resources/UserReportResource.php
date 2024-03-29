<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class UserReportResource extends JsonResource
{
    /**
     * Transform the resource into an array. This only entails the report itself.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'comment' => $this->comment,
            'reported_date' => $this->created_at,
            'reported_by_id' => $this->reported_by_user_id,
            'reported_by_name' => $this->reporter->username,
            'conversation' => (int) $this->conversation_id,
            'group_id' => $this->group_id,
            'id' => $this->id,
            'reason' => $this->reason,
        ];
    }

    public static function collection($resource) {
        return new UserReportResourceCollection($resource);
    }
}
