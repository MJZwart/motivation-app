<?php

use App\Models\Group;
use App\Models\GroupRole;
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
        Schema::table('group_roles', function (Blueprint $table) {
            $table->integer('position')->default(1);
        });

        $groups = Group::pluck('id');
        foreach ($groups as $group) {
            $allRoles = GroupRole::where('group_id', $group)->get();
            $amountOfRoles = $allRoles->count();
            foreach($allRoles as $role) {
                if ($role->owner) {
                    GroupRole::find($role->id)->update(['position' => 1]);
                }
                if ($role->member) {
                    GroupRole::find($role->id)->update(['position' => $amountOfRoles]);
                }
            }
            $otherRoles = $allRoles->filter(function($role) {
                return !$role->member && !$role->owner;
            });
            if ($otherRoles->count() == 1) GroupRole::find($otherRoles->first()->id)->update(['position' => 2]);
            else {
                foreach($otherRoles as $role) {
                    $amountOfPermissions = $role->can_edit + $role->can_manage_members + $role->can_moderate_messages;
                    $role->amount = $amountOfPermissions;
                }
                $sortedRoles = $otherRoles->sortByDesc('amount');
                $iterator = 0;
                foreach($sortedRoles as $role) {
                    GroupRole::find($role->id)->update(['position' => $iterator + 2]);
                    $iterator++;
                };
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
        Schema::table('group_roles', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
