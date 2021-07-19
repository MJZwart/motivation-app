<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Friend;
use App\Models\User;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0 ; $i < 30 ; $i++){
            $userId = rand(1, User::count());
            $friendId = rand(1, User::count());
            Friend::factory()
                ->create([
                    'user_id' => $userId,
                    'friend_id' => $friendId,
                    'accepted' => true,
                ]);
            Friend::factory()
                ->create([
                    'user_id' => $friendId,
                    'friend_id' => $userId,
                    'accepted' => true,
                ]);
        }

    }
}
