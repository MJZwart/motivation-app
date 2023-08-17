<?php

use App\Models\GroupExperiencePoint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('group_experience_points', function (Blueprint $table) {
            $table->id();
            $table->integer('level');
            $table->integer('experience_points');
        });

        
        $experiencePoints = 20000;
        $levelAmount = 273;
        for ($i = 1; $i < $levelAmount; $i++) {
            GroupExperiencePoint::insert([
                'level' => $i,
                'experience_points' => $experiencePoints,
            ]);
            if ($i < 50) $experiencePoints += 500;
            else $experiencePoints += 250;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_experience_points');
    }
};
