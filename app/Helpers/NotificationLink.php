<?php

namespace App\Helpers;

use JsonSerializable;
use PhpParser\Node\Expr\Cast\String_;
use stdClass;

class NotificationLink implements JsonSerializable
{

    protected $link;
    protected $text;

    /**
     * Creates a notification link that can be turned into JSON to store in the database
     * The $linkAPI is set to GET, PUT, POST or DELETE when it's an API call, or to null
     * if it's an internal router link.
     * The $linkUrl is the url to either the API call or internal link and is required.
     * The text is the translatable text as used in the front-end translate function.
     *
     * @param String $text
     * @param String $linkUrl
     * @param String|null $linkAPI
     */
    public function __construct(string $text, string $linkUrl, string $linkAPI = null)
    {
        $this->link = new stdClass();
        $this->link->api = $linkAPI;
        $this->link->url = $linkUrl;
        $this->text = $text;
    }

    /**
     * Static function to create an already serialized link class.
     * This is mainly used when there are multiple links in one notification
     *
     * @param String $text
     * @param String $linkUrl
     * @param String|null $linkAPI
     * @return Array
     */
    public static function create(string $text, string $linkUrl, string $linkAPI = null): array
    {
        $link = new NotificationLink($text, $linkUrl, $linkAPI);
        return $link->jsonSerialize();
    }

    /**
     * Turns the class into an array that can be JSON encoded
     *
     * @return Array
     */
    public function jsonSerialize(): array
    {
        return [
            'link' => $this->link,
            'text' => $this->text,
        ];
    }
}
