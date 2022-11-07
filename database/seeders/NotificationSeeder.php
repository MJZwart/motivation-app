<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notification::factory(20)->create();

        $cyptest1 = User::where('username', 'cyptest1')->first();
        Notification::factory(2)->create([
            'user_id' => $cyptest1->id,
        ]);
    }
}
