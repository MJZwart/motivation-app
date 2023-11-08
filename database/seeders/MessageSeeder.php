<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Faker\Generator;
use Illuminate\Container\Container;

class MessageSeeder extends Seeder
{
    protected $faker;
    public function __construct() {
        $this->faker = $this->withFaker();
    }
    protected function withFaker() {
        return Container::getInstance()->make(Generator::class);
    }
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
            $this->createMessage($this->faker->sentence, $user->id, $recipient, $conversationId);
            $this->createMessage($this->faker->sentence, $user->id, $recipient, $conversationId);
            $this->createMessage($this->faker->sentence, $recipient, $user->id, $conversationId);
            $this->createMessage($this->faker->sentence, $recipient, $user->id, $conversationId);
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
            $this->createMessage($this->faker->sentence, $user->id, $recipient, $conversationId);
            $this->createMessage($this->faker->sentence, $user->id, $recipient, $conversationId);
            $this->createMessage($this->faker->sentence, $recipient, $user->id, $conversationId);
            $this->createMessage($this->faker->sentence, $recipient, $user->id, $conversationId);
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
        $conversationId = random_int(11111, 99999);
        $cyptest1 = User::where('username', 'cyptest1')->first();
        $cyptest2 = User::where('username', 'cyptest2')->first();
        $this->createMessage($this->faker->sentence, $cyptest1->id, $cyptest2->id, $conversationId);
        $this->createMessage($this->faker->sentence, $cyptest2->id, $cyptest1->id, $conversationId);
        Conversation::create([
            'user_id' => $cyptest1->id,
            'recipient_id' => $cyptest2->id,
            'conversation_id' => $conversationId,
        ]);
        Conversation::create([
            'user_id' => $cyptest2->id,
            'recipient_id' => $cyptest1->id,
            'conversation_id' => $conversationId,
        ]);
    }

    private function createMessage(string $message, int $sender, int $recipient, string $conversationId) {
        Message::create([
            'message' => $message,
            'user_id' => $sender,
            'sender_id' => $sender,
            'recipient_id' => $recipient,
            'conversation_id' => $conversationId,
            'read' => true,
        ]);
        Message::create([
            'message' => $message,
            'user_id' => $recipient,
            'sender_id' => $sender,
            'recipient_id' => $recipient,
            'conversation_id' => $conversationId,
        ]);
    }
}
