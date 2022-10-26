<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();
        foreach($users as $user) {
            $recipient = 1;
            $conversationId = random_int(11111, 99999);
            if($user->id < count($users) - 1){
                $recipient = $user->id + 1;
            }
            Message::factory(2)->create([
                'sender_id' => $user->id,
                'recipient_id' => $recipient,
                'conversation_id' => $conversationId,
            ]);
            Conversation::create([
                'user_id' => $user->id,
                'recipient_id' => $recipient,
                'conversation_id' => $conversationId,
            ]);
            Conversation::create([
                'user_id' => $recipient,
                'recipient_id' => $user->id,
                'conversation_id' => $conversationId,
            ]);

            $recipient = 2;
            $conversationId =  random_int(11111, 99999);
            if($user->id < count($users) - 2){
                $recipient = $user->id + 2;
            }
            Message::factory(2)->create([
                'sender_id' => $user->id,
                'recipient_id' => $recipient,
                'conversation_id' => $conversationId,
            ]);
            Conversation::create([
                'user_id' => $user->id,
                'recipient_id' => $recipient,
                'conversation_id' => $conversationId,
            ]);
            Conversation::create([
                'user_id' => $recipient,
                'recipient_id' => $user->id,
                'conversation_id' => $conversationId,
            ]);
        }
    }
}
