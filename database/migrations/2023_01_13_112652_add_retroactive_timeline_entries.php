<?php

use App\Helpers\TimelineHandler;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = User::all();
        foreach($users as $user) {
            TimelineHandler::addToTimeline(
                $user->created_at, 
                $user->id, 
                TimelineHandler::JOIN, 
                TimelineHandler::USER_JOINED);
            $achievements = $user->achievements;
            foreach($achievements as $achievement) {
                TimelineHandler::addToTimeline(
                    $achievement->pivot->earned, 
                    $achievement->pivot->user_id, 
                    TimelineHandler::ACHIEVEMENT, 
                    TimelineHandler::ACHIEVEMENT_EARNED, 
                    ['name' => $achievement->name]);
            }
            $characters = $user->characters;
            foreach($characters as $character) {
                TimelineHandler::addToTimeline(
                    $character->created_at, 
                    $character->user_id, 
                    TimelineHandler::CHARACTER, 
                    TimelineHandler::CHARACTER_CREATED, 
                    ['name' => $character->name]);
            }
            $villages = $user->villages;
            foreach($villages as $village) {
                TimelineHandler::addToTimeline(
                    $village->created_at, 
                    $village->user_id, 
                    TimelineHandler::VILLAGE, 
                    TimelineHandler::VILLAGE_CREATED, 
                    ['name' => $village->name]);
            }
            $groups = $user->groups;
            foreach($groups as $group) {
                TimelineHandler::addToTimeline(
                    $group->pivot->joined, 
                    $group->pivot->user_id, 
                    TimelineHandler::GROUP, 
                    TimelineHandler::JOINED_GROUP, 
                    ['name' => $group->name]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
