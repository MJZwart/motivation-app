<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHandler {

    public static function create(int $userId, string $title, string $text, $link, bool $deleteOnAction) {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'text' => $text,
            'links' => $link,
            'delete_links_on_action' => $deleteOnAction,
        ]);
    }

    public static function createFromFriendRequest(int $userId, string $title, string $text, $friendRequest) {
        $acceptLink = new NotificationLink('accept', "/friend/request/$friendRequest->id/accept", 'POST');
        $denyLink = new NotificationLink('deny', "/friend/request/$friendRequest->id/deny", 'POST');
        $linkGroup = json_encode(array($acceptLink->jsonSerialize(), $denyLink->jsonSerialize()), JSON_UNESCAPED_SLASHES);
        NotificationHandler::create($userId, $title, $text, $linkGroup, true);
    }
}