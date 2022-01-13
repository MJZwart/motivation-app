<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVillagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->integer('economy')->default(1);
            $table->integer('economy_exp')->default(0);
            $table->integer('labour')->default(1);
            $table->integer('labour_exp')->default(0);
            $table->integer('craft')->default(1);
            $table->integer('craft_exp')->default(0);
            $table->integer('art')->default(1);
            $table->integer('art_exp')->default(0);
            $table->integer('community')->default(1);
            $table->integer('community_exp')->default(0);
            $table->integer('level')->default(1);
            $table->integer('experience')->default(0);
        });
        
        Schema::table('villages', function (Blueprint $table){
            $table->foreignId('user_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('villages');
    }
}
