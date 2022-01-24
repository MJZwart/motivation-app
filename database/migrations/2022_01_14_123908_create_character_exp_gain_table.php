<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterExpGainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_exp_gain');
    }
}
