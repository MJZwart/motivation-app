<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExperiencePointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $experiencePoints = 1000;
        for($i = 1 ; $i < 101 ; $i++){
            DB::table('experience_points')->insert([
                'level' => $i,
                'experience_points' => $experiencePoints,
            ]);
            $experiencePoints += 100;
        }

    }
}
