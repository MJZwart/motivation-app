<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVillageExpGainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('village_exp_gain', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('task_type');
            $table->integer('economy');
            $table->integer('labour');
            $table->integer('craft');
            $table->integer('art');
            $table->integer('community');
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
        Schema::dropIfExists('village_exp_gain');
    }
}
