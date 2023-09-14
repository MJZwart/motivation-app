<?php

use App\Models\Group;
use App\Models\GroupUserExp;
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
        Schema::create('group_user_daily_exp', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('group_user_id')->constrained('group_user');
            $table->foreignId('group_id')->constrained();
            $table->bigInteger('daily_exp')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user_daily_exp');
    }
};
