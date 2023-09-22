<?php

namespace Database\Seeders;

use App\Helpers\GroupRoleHandler;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\GroupApplication;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::factory(15)->create();
        $groups = Group::get();
        $users = User::pluck('id')->toArray();
        $this->createApplicationGroupForTesting($users);
        foreach($groups as $group) {
            GroupRoleHandler::createStandardGroupRoles($group->id);
            $amountOfUsers = rand(1, 10);
            shuffle($users);
            $this->createGroupUsers($group, array_slice($users, 0, $amountOfUsers));
        }
    }

    private function createGroupUsers(Group $group, array $users) {
        $first = true;
        foreach($users as $user) {
            DB::table('group_user')->insert([
                'group_id' => $group->id,
                'user_id' => $user,
                'joined' => Carbon::now()->subDays(rand(1, 365)),
                'rank' => $first ? 
                    GroupRoleHandler::getAdminRank($group->id)->id : 
                    GroupRoleHandler::getMemberRank($group->id)->id,
            ]);
            $first = false;
        }
    }

    private function createApplicationGroupForTesting(array $users) {
        DB::table('groups')->insert([
            'name' => 'application_test',
            'description' => 'application_test',
            'is_public' => 1,
            'require_application' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $group = Group::where('name', 'application_test')->first();
        GroupRoleHandler::createStandardGroupRoles($group->id);
        $adminRole = GroupRoleHandler::getAdminRank($group->id);
        DB::table('group_user')->insert([
            'user_id' => 21,
            'rank' => $adminRole->id,
            'group_id' => $group->id,
        ]);
        shuffle($users);
        for($i = 0; $i < 6; $i++) {
            GroupApplication::newApplication($group->id, $users[$i]);
        };
    }
}
