<?php

use App\Helpers\TimelineHandler;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            TimelineHandler::addJoinDateToTimeline($user);
            $achievements = $user->achievements;
            foreach($achievements as $achievement) {
                TimelineHandler::addAchievementToTimeline($achievement);
            }
            $characters = $user->characters;
            foreach($characters as $character) {
                TimelineHandler::addCharacterCreationToTimeline($character);
            }
            $villages = $user->villages;
            foreach($villages as $village) {
                TimelineHandler::addVillageCreationToTimeline($village);
            }
            $groups = $user->groups;
            foreach($groups as $group) {
                TimelineHandler::addGroupJoiningToTimeline($group);
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
