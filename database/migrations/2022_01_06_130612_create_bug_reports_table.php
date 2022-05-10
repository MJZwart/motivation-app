<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBugReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bug_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('page');
            $table->string('type');
            $table->integer('severity');
            $table->text('image_link')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->text('admin_comment')->nullable();
            $table->integer('status')->default(0); //0 = reported, 1 = in progress, 2 = done, 3 = resolved
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bug_reports');
    }
}
