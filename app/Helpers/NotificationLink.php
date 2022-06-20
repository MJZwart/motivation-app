<?php

namespace App\Helpers;

use JsonSerializable;
use stdClass;

class NotificationLink implements JsonSerializable {

    protected $link;
    protected $text;
    
    public function __construct($text, $linkUrl, $linkAPI = null)
    {
        $this->link = new stdClass();
        $this->link->api = $linkAPI;
        $this->link->url = $linkUrl;
        $this->text = $text;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'link' => $this->link,
            'text' => $this->text,
        ];
    }
}