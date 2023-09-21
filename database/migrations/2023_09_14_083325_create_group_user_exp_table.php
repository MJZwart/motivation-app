<?php

use App\Models\Group;
use App\Models\GroupUserExp;
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
        Schema::create('group_user_exp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_user_id')->constrained('group_user', 'id');
            $table->foreignId('group_id')->constrained();
            $table->bigInteger('exp_gained')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user_exp');
    }
};
