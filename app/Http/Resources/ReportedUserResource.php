<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ReportedUser;
use App\Http\Resources\ReportedUserReportResource;

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
            'name' => $this->username,
            'report_amount' => ReportedUser::where('reported_user_id', $this->id)->get()->count(),
            'last_report_date' => $this->getLatestReport()->created_at->toDateTimeString(),
            'reports' => ReportedUserReportResource::collection($this->getReports()),
        ];
    }
}
