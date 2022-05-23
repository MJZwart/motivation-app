<?php

namespace Database\Seeders;

use App\Models\BannedUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BannedUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nextYear = Carbon::now()->addYear();
        $nextWeek = Carbon::now()->addWeek();
        User::factory(1)
            ->hasCharacters(1)
            ->hasTaskLists(3)
            ->create([
                'username' => 'bannedYear',
                'banned_until' => $nextYear]);
        User::factory(1)
            ->hasCharacters(1)
            ->hasTaskLists(3)
            ->create([
                'username' => 'bannedWeek',
                'banned_until' => $nextWeek]);
        
        $bannedYear = User::where('username', 'bannedYear')->first();
        $bannedWeek = User::where('username', 'bannedWeek')->first();
        $admin = User::where('admin', true)->first();

        BannedUser::create([
            'user_id' => $bannedYear->id,
            'admin_id' => $admin->id,
            'reason' => 'Harassment',
            'days' => 365,
            'banned_until' => $nextYear,
        ]);
        BannedUser::create([
            'user_id' => $bannedWeek->id,
            'admin_id' => $admin->id,
            'reason' => 'Spam',
            'days' => 7,
            'banned_until' => $nextWeek,
        ]);  
    }
}
