<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ReportedUser;
use App\Http\Resources\ReportedUserReportResource;
use App\Http\Resources\BannedUserResource;

class ReportedUserResource extends JsonResource
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
            'username' => $this->username,
            'report_amount' => ReportedUser::where('reported_user_id', $this->id)->count(),
            'last_report_date' => $this->getLatestReport()->value('created_at')->toDateTimeString(),
            'reports' => ReportedUserReportResource::collection($this->getReports()),
            'banned' => $this->getBannedUserResource(),
            'banned_until' => $this->banned_until,
        ];
    }
}
