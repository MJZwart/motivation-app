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
        Schema::table('feedback', function (Blueprint $table) {
            $table->string('diagnostics')->nullable();
        });
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->string('diagnostics')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn('diagnostics');
        });
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->dropColumn('diagnostics');
        });
    }
};
