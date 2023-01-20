<?php

namespace App\Helpers;

use App\Models\Achievement;
use App\Models\Character;
use App\Models\Group;
use App\Models\TimelineAction;
use App\Models\User;
use App\Models\Village;
use Carbon\Carbon;

class TimelineHandler
{
    public const JOIN = 'JOIN';
    public const ACHIEVEMENT = 'ACHIEVEMENT';
    public const CHARACTER = 'CHARACTER';
    public const VILLAGE = 'VILLAGE';
    public const GROUP = 'GROUP';

    public const USER_JOINED = 'user-joined';
    public const ACHIEVEMENT_EARNED = 'achievement-earned';
    public const CHARACTER_CREATED = 'character-created';
    public const VILLAGE_CREATED = 'village-created';
    public const JOINED_GROUP = 'joined-group';
    public const CREATED_GROUP = 'created-group';
    public const LEFT_GROUP = 'left-group';
    public const LEVEL_UP = 'level-up';

    public static function addJoinDateToTimeline(User $user) {
        TimelineHandler::addToTimeline(
            $user->created_at, 
            $user->id, 
            TimelineHandler::JOIN, 
            TimelineHandler::USER_JOINED);
    }

    public static function addAchievementToTimeline(Achievement $achievement, int $userId) {
        TimelineHandler::addToTimeline(
            Carbon::now()->toString(),
            $userId, 
            TimelineHandler::ACHIEVEMENT, 
            TimelineHandler::ACHIEVEMENT_EARNED, 
            ['name' => $achievement->name]);
    }

    public static function addGroupJoiningToTimeline(Group $group, int $userId) {
        TimelineHandler::addToTimeline(
            $group->pivot->joined, 
            $userId, 
            TimelineHandler::GROUP, 
            TimelineHandler::JOINED_GROUP, 
            ['name' => $group->name]);
    }
    public static function addGroupCreationToTimeline(Group $group, int $userId) {
        TimelineHandler::addToTimeline(
            $group->created_at,
            $userId,
            TimelineHandler::GROUP,
            TimelineHandler::CREATED_GROUP,
            ['name' => $group->name]);
    }
    public static function addGroupLeavingToTimeline(Group $group, int $userId) {
        TimelineHandler::addToTimeline(
            Carbon::now()->toString(),
            $userId,
            TimelineHandler::GROUP,
            TimelineHandler::LEFT_GROUP,
            ['name' => $group->name]);
    }

    public static function addNewRewardToTimeline(string $rewardName, int $userId, string $type, string $message) {
        TimelineHandler::addToTimeline(
            Carbon::now()->toString(),
            $userId,
            $type,
            $message,
            ['name' => $rewardName]
        );
    }
    public static function addLevelUpToTimeline(string $rewardName, int $userId, int $level, $type) {
        TimelineHandler::addToTimeline(
            Carbon::now()->toString(),
            $userId,
            $type,
            TimelineHandler::LEVEL_UP,
            ['level' => $level, 'name' => $rewardName]
        );
    }

    public static function addToTimeline(string $timestamp, int $userId, string $type, string $action, string | array $params = null) {
        $encodedParams = $params ? json_encode($params) : null;
        TimelineAction::create([
            'timestamp' => $timestamp,
            'user_id' => $userId,
            'type' => $type,
            'action' => $action,
            'params' => $encodedParams,
        ]);
    }
}