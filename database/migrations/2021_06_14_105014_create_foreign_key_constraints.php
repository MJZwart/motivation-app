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
            $table->foreign('user_id_1')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('user_id_2')
                ->references('id')->on('users')
                ->onDelete('cascade');
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
            $table->dropForeign('user_id_1');
            $table->dropForeign('user_id_2');
        });
    }
}
