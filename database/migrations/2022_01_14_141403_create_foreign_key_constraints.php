<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table){
            $table->foreignId('user_id')->constrained();
            $table->foreignId('task_list_id')->constrained();
            $table->foreign('super_task_id')
                ->references('id')->on('tasks')
                ->onDelete('set null');
        });
        Schema::table('task_lists', function (Blueprint $table){
            $table->foreignId('user_id')->constrained();
        });
        Schema::table('notifications', function (Blueprint $table){
            $table->foreignId('user_id')->constrained();
        });
        Schema::table('friends', function (Blueprint $table){
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('friend_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
        Schema::table('characters', function (Blueprint $table){
            $table->foreignId('user_id')->constrained();
        });
        Schema::table('repeatable_tasks_completed', function (Blueprint $table){
            $table->foreignId('user_id')->constrained();
            $table->foreignId('task_id')->constrained();
        });
        Schema::table('achievements_earned', function (Blueprint $table){
            $table->foreignId('user_id')->constrained();
            $table->foreignId('achievement_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table){
            $table->dropForeign('user_id');
            $table->dropForeign('super_task_id');
            $table->dropForeign('task_list_id');
        });
        Schema::table('task_lists', function (Blueprint $table){
            $table->dropForeign('user_id');
        });
        Schema::table('notifications', function (Blueprint $table){
            $table->dropForeign('user_id');
        });
        Schema::table('friends', function (Blueprint $table){
            $table->dropForeign('user_id');
            $table->dropForeign('friend_id');
        });
        Schema::table('characters', function (Blueprint $table){
            $table->dropForeign('user_id');
        });
        Schema::table('repeatable_tasks_completed', function (Blueprint $table){
            $table->dropForeign('user_id');
            $table->dropForeign('task_id');
        });
        Schema::table('achievements_earned', function (Blueprint $table){
            $table->dropForeign('user_id');
            $table->dropForeign('achievement_id');
        });
    }
}
