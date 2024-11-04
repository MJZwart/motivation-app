<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('rewards_int')->default(1);
        });
        DB::table('users')->where('rewards', 'CHARACTER')->orWhere('rewards', 'NONE')->update(['rewards_int' => 0]);
        DB::table('users')->where('rewards', 'VILLAGE')->update(['rewards_int' => 1]);
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rewards');
            $table->renameColumn('rewards_int', 'rewards');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('rewards_text')->default('VILLAGE');
        });
        DB::table('users')->where('rewards', 0)->update(['rewards_text' => 'NONE']);
        DB::table('users')->where('rewards', 1)->update(['rewards_text' => 'VILLAGE']);
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rewards');
            $table->renameColumn('rewards_text', 'rewards');
        });
    }
};
