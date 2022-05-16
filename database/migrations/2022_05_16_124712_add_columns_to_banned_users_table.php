<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBannedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banned_users', function (Blueprint $table) {
            $table->timestamp('early_release')->nullable();
            $table->text('early_release_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banned_users', function (Blueprint $table) {
            $table->dropColumn('early_release');
            $table->dropColumn('early_release_details');
        });
    }
}
