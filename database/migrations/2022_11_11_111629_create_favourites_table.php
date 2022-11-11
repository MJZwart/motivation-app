<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favourites', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('difficulty');
            $table->string('type'); 
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('favourite')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favourites');
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('favourite');
        });
    }
};
