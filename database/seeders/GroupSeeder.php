<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
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
            $amountOfUsers = rand(1, 10);
            shuffle($users);
            DB::table('group_user')->insert([
                'group_id' => $group->id,
                'user_id' => $users[0],
                'joined' => Carbon::now()->subDays(rand(1, 365)),
                'rank' => 'admin',
            ]);
            for($i = 1 ; $i < $amountOfUsers ; $i++) {
                DB::table('group_user')->insert([
                    'group_id' => $group->id,
                    'user_id' => $users[$i],
                    'joined' => Carbon::now()->subDays(rand(1, 365)),
                    'rank' => 'member',
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
        DB::table('group_user')->insert([
            'user_id' => 21,
            'rank' => 'admin',
            'group_id' => $group->id,
        ]);
        shuffle($users);
        for($i = 0; $i < 6; $i++) {
            $group->applications()->attach($users[$i]);
        };
    }
}
