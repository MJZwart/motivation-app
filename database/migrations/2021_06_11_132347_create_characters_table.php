<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characters');
    }
}
