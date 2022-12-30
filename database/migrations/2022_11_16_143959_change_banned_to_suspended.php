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
        Schema::rename('banned_users', 'suspended_users');
        Schema::table('suspended_users', function(Blueprint $table) {
            $table->renameColumn('banned_until', 'suspended_until');
            $table->renameColumn('ban_edit_comment', 'suspension_edit_comment');
            $table->renameColumn('ban_edit_log', 'suspension_edit_log');
        });
        Schema::rename('group_bans', 'group_suspensions');        
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('banned_until', 'suspended_until');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('suspended_users', 'banned_users');
        Schema::table('banned_users', function(Blueprint $table) {
            $table->renameColumn('suspended_until', 'banned_until');
            $table->renameColumn('suspension_edit_comment', 'ban_edit_comment');
            $table->renameColumn('suspension_edit_log', 'ban_edit_log');
        });
        Schema::rename('group_suspensions', 'group_bans');
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('suspended_until', 'banned_until');
        });
    }
};
