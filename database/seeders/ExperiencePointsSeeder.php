<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExperiencePoint;

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
        $levelAmount = 2000;
        if (app()->environment(['local'])) 
            $levelAmount = 130;
        for($i = 1 ; $i < $levelAmount ; $i++){
            ExperiencePoint::insert([
                'level' => $i,
                'experience_points' => $experiencePoints,
            ]);
            if ($i < 50) $experiencePoints += 50;
            else if ($i < 112) $experiencePoints += 25;
        }

    }
}
