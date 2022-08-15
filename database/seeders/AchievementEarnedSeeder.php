<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AchievementEarnedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment(['local', 'testing', 'staging'])) {
            for ($i = 0; $i < 40; $i++) {
                DB::table('achievements_earned')->insertOrIgnore([
                    'user_id' => rand(1, User::count()),
                    'achievement_id' => rand(1, Achievement::count()),
                ]);
            }
        }
    }
}
