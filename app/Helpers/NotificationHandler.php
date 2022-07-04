<?php

namespace App\Helpers;

use App\Models\Friend;
use App\Models\Notification;

class NotificationHandler {

    /**
     * Creates a notification
     *
     * @param int $userId
     * @param String $title
     * @param String $text
     * @param String|null $link
     * @param Boolean|null $deleteOnAction
     * @return void
     */
    public static function create(int $userId, string $title, string $text, $link = null, bool $deleteOnAction = false) {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'text' => $text,
            'links' => $link,
            'link_active' => $link ? true : null,
            'delete_links_on_action' => $deleteOnAction,
        ]);
    }

    /**
     * Creates a notification from a friend request. The function builds a link to accept
     * or deny the request, given the parameters.
     *
     * @param int $userId
     * @param String $title
     * @param String $text
     * @param Friend $friendRequest
     * @return void
     */
    public static function createFromFriendRequest(int $userId, string $title, string $text, Friend $friendRequest) {
        $acceptLink = NotificationLink::create('accept', "/friend/request/$friendRequest->id/accept", 'POST');
        $denyLink = NotificationLink::create('deny', "/friend/request/$friendRequest->id/deny", 'POST');
        $linkGroup = NotificationHandler::createJson($acceptLink, $denyLink);
        NotificationHandler::create($userId, $title, $text, $linkGroup, true);
    }
    
    /**
     * Creates a notification
     *
     * @param int $userId
     * @param String $title
     * @param String $text
     * @param String|null $link
     * @param String|null $linkText
     * @return void
     */
    public static function createFromAdminDashboard(int $userId, string $title, string $text, $link = null, string $linkText = null) {
        if ($link) {
            $serializedLink = NotificationLink::create($linkText, $link);
            NotificationHandler::create($userId, $title, $text, NotificationHandler::createJson($serializedLink));
        } else {
            NotificationHandler::create($userId, $title, $text);
        }
    }

    /**
     * Creates a json string out of one or multiple NotificationLink objects.
     *
     * @param String ...$links
     * @return String
     */
    private static function createJson(...$links): String
    {
        return json_encode($links, JSON_UNESCAPED_SLASHES);
    }
}