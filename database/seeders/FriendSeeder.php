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
        $userCount = User::count();
        for($i = 0 ; $i < 30 ; $i++){
            $userId = rand(1, $userCount);
            $friendId = rand(1, $userCount);
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
        $cyptest1 = User::where('username', 'cyptest1')->first();
        $cyptest2 = User::where('username', 'cyptest2')->first();
        Friend::factory()
            ->create([
                'user_id' => $cyptest1->id,
                'friend_id' => $cyptest2->id,
                'accepted' => true,
            ]);
        Friend::factory()
            ->create([
                'user_id' => $cyptest2->id,
                'friend_id' => $cyptest1->id,
                'accepted' => true,
            ]);
    }
}
