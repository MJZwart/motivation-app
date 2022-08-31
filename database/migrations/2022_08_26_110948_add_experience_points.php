<?php

use App\Models\ExperiencePoint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $experiencePoints = 2000;
        $levelAmount = 130;
        for ($i = 1; $i < $levelAmount; $i++) {
            ExperiencePoint::insert([
                'level' => $i,
                'experience_points' => $experiencePoints,
            ]);
            if ($i < 50) $experiencePoints += 50;
            else if ($i < 112) $experiencePoints += 25;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
