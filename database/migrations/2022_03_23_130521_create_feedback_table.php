<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type');
            $table->longText('text');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('archived')->default(false);
        });
        Schema::table('feedback', function (Blueprint $table){
            $table->foreign('user_id')
                ->references('id')->on('tasks')
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
        Schema::dropIfExists('feedback');
    }
}
