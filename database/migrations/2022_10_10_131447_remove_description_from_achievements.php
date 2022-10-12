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
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropColumn('trigger_description');
        });

        Schema::dropIfExists('achievement_triggers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->string('trigger_description');
        });
        
        Schema::create('achievement_triggers', function (Blueprint $table) {
            $table->id();
            $table->string('trigger_type');
            $table->string('trigger_description');
        });
    }
};
