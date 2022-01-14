<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('display_picture')->nullable();
            $table->boolean('admin')->default(false);
            $table->string('rewards')->default('CHARACTER');
            $table->boolean('show_achievements')->default(true);
            $table->boolean('show_friends')->default(true);
            $table->boolean('show_character')->default(true);
            $table->boolean('first_login')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
