<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;
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
        $trigger_descriptions = ['Created %d task%s.', 'Completed %d task%s.', 'Complete a repeatable task %d time%s.', 'Add %d friend%s.'];
        $trigger_amounts = [1, 5, 10, 25, 50];
        $achievement_names =
            [
                // Tasks made
                [
                    'First steps',
                    'First leap',
                    'First walk',
                    'First hike',
                    'First marathon',
                ],
                // Tasks completed
                [
                    'Getting ready',
                    'Making progress',
                    'Getting stuff done',
                    'Making waves',
                    'Completionist in making',
                ],
                // Repeatable completed
                [
                    'Let\'s do this again',
                    'One more time',
                    'Keep at it',
                    'Forming a habit',
                    'Second nature',
                ],
                // Friends made
                [
                    'BFF',
                    'Social gathering',
                    'That\'s a party',
                    'Well connected',
                    'Influencer',
                ],
            ];

        for ($i = 0; $i < sizeOf($trigger_types); $i++) {
            DB::table('achievement_triggers')->insert([
                'trigger_type' => $trigger_types[$i],
                'trigger_description' => $trigger_descriptions[$i],
            ]);
            for ($j = 0; $j < sizeOf($trigger_amounts); $j++) {
                $plural = $trigger_amounts[$j] > 1 ? 's' : '';
                Achievement::create([
                    'name' => $achievement_names[$i][$j],
                    'trigger_description' => sprintf($trigger_descriptions[$i], $trigger_amounts[$j], $plural),
                    'trigger_type' => $trigger_types[$i],
                    'trigger_amount' => $trigger_amounts[$j],
                ]);
            }
        }
    }
}
