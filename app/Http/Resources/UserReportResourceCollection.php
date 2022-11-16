<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserReportResourceCollection extends ResourceCollection
{
    /** @var User */
    protected $user;

    /**
     * Set the user to get all the necessary user information linked to the reports.
     *
     * @param User $user
     * @return this
     */
    public function setUser($user) {
        $this->user = $user;
        return $this;
    }
    /**
     * Uses the pre-set User to set the details only once per report group.
     * After setting the user details, bundles the reports as a collection of UserReportResource
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
            'suspended' => $this->user->getSuspendedUserResource(),
            'suspended_until' => $this->user->suspended_until,
            'reports' => parent::toArray($request),
        ];
    }
}
