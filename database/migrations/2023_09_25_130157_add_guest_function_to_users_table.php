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
            $table->boolean('guest')->default(false);
            $table->string('login_token')->nullable();
            $table->string('email')->nullable()->change();
            $table->dropUnique('users_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('guest');
            $table->dropColumn('login_token');
            $table->string('email')->unique()->nullable(false)->change();
        });
    }
};