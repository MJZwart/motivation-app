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
            $table->dropForeign('tasks_user_id_foreign');
            $table->dropForeign('tasks_super_task_id_foreign');
            $table->dropForeign('tasks_task_list_id_foreign');
        });
        Schema::table('task_lists', function (Blueprint $table){
            $table->dropForeign('task_lists_user_id_foreign');
        });
        Schema::table('notifications', function (Blueprint $table){
            $table->dropForeign('notifications_user_id_foreign');
        });
        Schema::table('friends', function (Blueprint $table){
            $table->dropForeign('friends_user_id_foreign');
            $table->dropForeign('friends_friend_id_foreign');
        });
        Schema::table('characters', function (Blueprint $table){
            $table->dropForeign('characters_user_id_foreign');
        });
        Schema::table('repeatable_tasks_completed', function (Blueprint $table){
            $table->dropForeign('repeatable_tasks_completed_user_id_foreign');
            $table->dropForeign('repeatable_tasks_completed_task_id_foreign');
        });
        Schema::table('achievements_earned', function (Blueprint $table){
            $table->dropForeign('achievements_earned_user_id_foreign');
            $table->dropForeign('achievements_earned_achievement_id_foreign');
        });
    }
}
