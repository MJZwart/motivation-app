<?php

use App\Helpers\GroupRoleHandler;
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
        Schema::create('group_roles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->boolean('can_delete')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_manage_members')->default(false);
            $table->boolean('owner')->default(false);
            $table->boolean('member')->default(false);
            
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
        });

        $groups = Group::all();
        foreach ($groups as $group) {
            GroupRoleHandler::createStandardGroupRoles($group->id);
            $roles = $group->roles;
            foreach($group->groupUsers as $member) {
                if($member->rank === 'admin')
                    $member->rank = $roles->where('owner', true)->first()->id;
                else
                    $member->rank = $roles->where('member', true)->first()->id;
                    $member->save();
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
        $groups = Group::all();
        foreach ($groups as $group) {
            $roles = $group->roles;
            foreach($group->groupUsers as $member) {
                if($member->rank === $roles->where('owner', true)->first()->id)
                    $member->rank = 'admin';
                else
                    $member->rank = 'member';
                $member->save();
            }
        }
        Schema::dropIfExists('group_roles');
    }
};
