<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageResourceCollection extends ResourceCollection
{
    protected $user_id;

    public function setUserId($userId) {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function(MessageResource $resource) use($request) {
            return $resource->setUserId($this->user_id)->toArray($request);
        })->all();
    }
}
