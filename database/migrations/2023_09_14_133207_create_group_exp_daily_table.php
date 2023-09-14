<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('group_exp_daily', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained();
            $table->date('date')->default(Carbon::now()->toDateString());
            $table->bigInteger('exp_gained')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_exp_daily');
    }
};
