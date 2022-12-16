<?php

use App\Models\GroupRole;
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
        Schema::table('group_roles', function (Blueprint $table) {
            $table->boolean('can_moderate_messages')->default(false);
        });

        GroupRole::where('owner', true)->update(['can_moderate_messages' => true]);
        GroupRole::where('owner', false)->where('member', false)->update(['can_moderate_messages' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_roles', function (Blueprint $table) {
            $table->dropColumn('can_moderate_messages');
        });
    }
};
