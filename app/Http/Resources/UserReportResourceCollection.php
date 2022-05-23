<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserReportResourceCollection extends ResourceCollection
{
    protected $user;

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->user->id,
            'username' => $this->user->username,
            'report_amount' => count(parent::toArray($request)),
            'last_report_date' => parent::toArray($request)[0]['reported_date'],
            'banned' => $this->user->getBannedUserResource(),
            'banned_until' => $this->user->banned_until,
            'reports' => parent::toArray($request),
        ];
    }
}
