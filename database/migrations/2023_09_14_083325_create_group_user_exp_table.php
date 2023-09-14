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
            $table->foreignId('group_user_id')->constrained('group_user');
            $table->foreignId('group_id')->constrained();
            $table->bigInteger('total_exp')->default(0);
        });

        foreach(Group::all() as $group) {
            foreach($group->groupUsers as $groupUser) {
                GroupUserExp::create([
                    'group_user_id' => $groupUser->id,
                    'group_id' => $group->id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user_exp');
    }
};
