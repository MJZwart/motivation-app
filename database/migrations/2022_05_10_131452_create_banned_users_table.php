<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banned_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('reason');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->integer('days');
            $table->dateTime('banned_until')->useCurrent();
        });
        Schema::table('banned_users', function (Blueprint $table){
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->foreign('admin_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banned_users');
    }
}
