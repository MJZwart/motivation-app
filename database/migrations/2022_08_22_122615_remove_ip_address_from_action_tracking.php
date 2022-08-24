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
        Schema::table('action_tracking', function (Blueprint $table) {
            $table->dropColumn('ip_address');
            $table->dropForeign('action_tracking_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_tracking', function (Blueprint $table) {
            $table->string('ip_address');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }
};
