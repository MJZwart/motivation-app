<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExampleTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example_task', function (Blueprint $table) {
            $table->id();
            $table->integer('difficulty')->default(3); //Difficulty between 1 and 5
            $table->integer('type')->default(1); //Type 1 is generic, rewards divided evenly
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('super_task_id')->nullable();
            $table->string('repeatable')->default('NONE'); //NONE, DAILY, WEEKLY, MONTHLY
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('example_task');
    }
}
