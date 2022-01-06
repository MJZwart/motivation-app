<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBugReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bug_report', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('page');
            $table->string('type');
            $table->integer('severity');
            $table->unsignedBigInteger('user_id');
            $table->string('comment');
            $table->string('admin_comment')->nullable();
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
        Schema::dropIfExists('bug_report');
    }
}
