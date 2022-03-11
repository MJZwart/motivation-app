<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            UserSeeder::class,
            TaskSeeder::class,
            NotificationSeeder::class,
            FriendSeeder::class,
            AchievementSeeder::class,
            ExperiencePointsSeeder::class,
            BalancingSeeder::class,
            MessageSeeder::class,
            BugReportSeeder::class,
            GroupSeeder::class,
        ]);
    }
}
