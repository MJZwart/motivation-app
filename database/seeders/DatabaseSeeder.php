<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ExampleTaskSeeder::class,
            AchievementSeeder::class,
            ExperiencePointsSeeder::class,
            BalancingSeeder::class,
        ]);
        if (App::environment(['local', 'testing', 'staging'])) {
            $this->call([
                UserSeeder::class,
                TaskSeeder::class,
                NotificationSeeder::class,
                FriendSeeder::class,
                MessageSeeder::class,
                BugReportSeeder::class,
                GroupSeeder::class,
                FeedbackSeeder::class,
                ReportedUserSeeder::class,
                BannedUsersSeeder::class,
                AchievementEarnedSeeder::class,
            ]);
        }
    }
}
