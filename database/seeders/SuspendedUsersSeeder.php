<?php

namespace Database\Seeders;

use App\Models\SuspendedUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SuspendedUsersSeeder extends Seeder
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
                'username' => 'suspendedYear',
                'suspended_until' => $nextYear]);
        User::factory(1)
            ->hasCharacters(1)
            ->hasTaskLists(3)
            ->create([
                'username' => 'suspendedWeek',
                'suspended_until' => $nextWeek]);
        
        $suspendedYear = User::where('username', 'suspendedYear')->first();
        $suspendedWeek = User::where('username', 'suspendedWeek')->first();
        $admin = User::where('admin', true)->first();

        SuspendedUser::create([
            'user_id' => $suspendedYear->id,
            'admin_id' => $admin->id,
            'reason' => 'Harassment',
            'days' => 365,
            'suspended_until' => $nextYear,
        ]);
        SuspendedUser::create([
            'user_id' => $suspendedWeek->id,
            'admin_id' => $admin->id,
            'reason' => 'Spam',
            'days' => 7,
            'suspended_until' => $nextWeek,
        ]);  
    }
}
