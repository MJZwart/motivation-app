<?php

namespace Database\Seeders;

use App\Helpers\GroupRoleHandler;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\GroupApplication;
use App\Models\GroupRole;
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
        foreach($groups as $group) {
            GroupRoleHandler::createStandardGroupRoles($group->id);
            $amountOfUsers = rand(1, 10);
            shuffle($users);
            DB::table('group_user')->insert([
                'group_id' => $group->id,
                'user_id' => $users[0],
                'joined' => Carbon::now()->subDays(rand(1, 365)),
                'rank' => GroupRole::where('group_id', $group->id)->where('owner', true)->first()->id,
            ]);
            for($i = 1 ; $i < $amountOfUsers ; $i++) {
                DB::table('group_user')->insert([
                    'group_id' => $group->id,
                    'user_id' => $users[$i],
                    'joined' => Carbon::now()->subDays(rand(1, 365)),
                    'rank' => GroupRole::where('group_id', $group->id)->where('member', true)->first()->id,
                ]);
            }
        }
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
        $adminRole = GroupRole::where('group_id', $group->id)->where('owner', true)->first();
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
