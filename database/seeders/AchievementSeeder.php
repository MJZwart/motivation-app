<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trigger_types = ['TASKS_MADE', 'TASKS_COMPLETED', 'REPEATABLE_COMPLETED', 'FRIENDS'];
        $trigger_amounts = [1, 5, 10, 25, 50];
        
        for($i = 0 ; $i < sizeOf($trigger_types) ; $i++){
            for($j = 0 ; $j < sizeOf($trigger_amounts) ; $j++){
                Achievement::factory()->create([
                    'trigger_type' => $trigger_types[$i],
                    'trigger_amount' => $trigger_amounts[$j],
                ]);
            }
        }

        for($i = 0 ; $i < 40 ; $i++){
            DB::table('achievements_earned')->insertOrIgnore([
                'user_id' => rand(1, User::count()),
                'achievement_id' => rand(1, Achievement::count()),
            ]);
        }

    }
}
