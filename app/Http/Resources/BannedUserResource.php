<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StrippedUserResource;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class BannedUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'created_at' => $this->created_at->toDateTimeString(),
            'time_since' => $this->created_at->diffForHumans(Carbon::now(),[
                'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
            ]),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'reason' => $this->reason,
            'days' => $this->days,
            'admin' => new StrippedUserResource($this->admin),
            'banned_until' => $this->banned_until,
            'past' => Carbon::parse($this->banned_until)->lessThan(Carbon::now()),
            'banned_until_time' => Carbon::parse($this->banned_until)->diffForHumans(Carbon::now(), ['syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW])
        ];
    }
}
