<?php

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
        Schema::drop('characters');
        Schema::drop('character_exp_gain');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->integer('strength')->default(1);
            $table->integer('strength_exp')->default(0);
            $table->integer('agility')->default(1);
            $table->integer('agility_exp')->default(0);
            $table->integer('endurance')->default(1);
            $table->integer('endurance_exp')->default(0);
            $table->integer('intelligence')->default(1);
            $table->integer('intelligence_exp')->default(0);
            $table->integer('charisma')->default(1);
            $table->integer('charisma_exp')->default(0);
            $table->integer('level')->default(1);
            $table->integer('experience')->default(0);
            $table->boolean('active')->default(true);
            $table->integer('coins')->default(0);
        });
        Schema::create('character_exp_gain', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('task_type');
            $table->integer('strength');
            $table->integer('agility');
            $table->integer('endurance');
            $table->integer('intelligence');
            $table->integer('charisma');
            $table->integer('level');
            $table->integer('coins')->default(0);
        });
        DB::table('character_exp_gain')->where('coins', 0)->update(['coins' => 7]);
        DB::table('character_exp_gain')->insert([
            'task_type' => 'GENERIC',
            'strength' => 3,
            'agility' => 3,
            'endurance' => 3,
            'intelligence' => 3,
            'charisma' => 3,
            'level' => 10,
        ]);
        DB::table('character_exp_gain')->insert([
            'task_type' => 'PHYSICAL',
            'strength' => 6,
            'agility' => 6,
            'endurance' => 4,
            'intelligence' => 1,
            'charisma' => 1,
            'level' => 10,
        ]);
        DB::table('character_exp_gain')->insert([
            'task_type' => 'MENTAL',
            'strength' => 2,
            'agility' => 1,
            'endurance' => 3,
            'intelligence' => 7,
            'charisma' => 5,
            'level' => 10,
        ]);
        DB::table('character_exp_gain')->insert([
            'task_type' => 'SOCIAL',
            'strength' => 2,
            'agility' => 3,
            'endurance' => 2,
            'intelligence' => 3,
            'charisma' => 8,
            'level' => 10,
        ]);
    }
};
