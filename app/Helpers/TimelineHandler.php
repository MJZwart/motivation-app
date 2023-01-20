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


    public static function addJoinDateToTimeline(User $user) {
        TimelineHandler::addToTimeline(
            $user->created_at, 
            $user->id, 
            TimelineHandler::JOIN, 
            TimelineHandler::USER_JOINED);
    }

    public static function addAchievementToTimeline(Achievement $achievement) {
        TimelineHandler::addToTimeline(
            $achievement->pivot->earned, 
            $achievement->pivot->user_id, 
            TimelineHandler::ACHIEVEMENT, 
            TimelineHandler::ACHIEVEMENT_EARNED, 
            ['name' => $achievement->name]);
    }

    public static function addCharacterCreationToTimeline(Character $character) {
        TimelineHandler::addToTimeline(
            $character->created_at, 
            $character->user_id, 
            TimelineHandler::CHARACTER, 
            TimelineHandler::CHARACTER_CREATED, 
            ['name' => $character->name]);
    }
    public static function addVillageCreationToTimeline(Village $village) {
        TimelineHandler::addToTimeline(
            $village->created_at, 
            $village->user_id, 
            TimelineHandler::VILLAGE, 
            TimelineHandler::VILLAGE_CREATED, 
            ['name' => $village->name]);
    }

    public static function addGroupJoiningToTimeline(Group $group, int $userId = null) {
        TimelineHandler::addToTimeline(
            $group->pivot->joined, 
            $userId ? $userId : $group->pivot->user_id, 
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