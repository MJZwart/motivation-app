<?php

use App\Models\GlobalSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        GlobalSetting::create([
            'key' => 'group_max_exp_per_day',
            'value' => 10000,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $groupExpSetting = GlobalSetting::where('key', 'group_max_exp_per_day')->first();
        $groupExpSetting->delete();
    }
};
