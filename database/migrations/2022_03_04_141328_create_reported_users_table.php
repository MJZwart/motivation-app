<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('reported_user_id');
            $table->unsignedBigInteger('reported_by_user_id');
            $table->longText('comment')->nullable();
            $table->string('reason');
            $table->string('conversation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reported_user');
    }
}
