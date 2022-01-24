<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExampleTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('difficulty')->default(3); //Difficulty between 1 and 5
            $table->string('type')->default('GENERIC');
            $table->string('name');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('example_tasks');
    }
}
