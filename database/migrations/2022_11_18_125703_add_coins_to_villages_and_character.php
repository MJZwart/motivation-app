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
        Schema::table('villages', function (Blueprint $table) {
            $table->integer('coins')->default(0);
        });
        Schema::table('characters', function (Blueprint $table) {
            $table->integer('coins')->default(0);
        });
        Schema::table('village_exp_gain', function (Blueprint $table) {
            $table->integer('coins')->default(0);
        });
        Schema::table('character_exp_gain', function (Blueprint $table) {
            $table->integer('coins')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn('coins');
        });
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('coins');
        });
        Schema::table('village_exp_gain', function (Blueprint $table) {
            $table->dropColumn('coins');
        });
        Schema::table('character_exp_gain', function (Blueprint $table) {
            $table->dropColumn('coins');
        });
    }
};
