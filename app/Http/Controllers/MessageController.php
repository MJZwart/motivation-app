<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Http\Resources\ConversationOverviewResource;
use App\Http\Requests\SendMessageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MessageController extends Controller
{

    public function getConversations() {
        return ConversationOverviewResource::collection(
            Conversation::where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get());
    }

    public function sendMessage(SendMessageRequest $request) {
        $validated = $request->validated();
        $validated['sender_id'] = Auth::user()->id;

        if(!array_key_exists('conversation_id', $validated)) {
            $validated['conversation_id'] = $this->getConversationId(Auth::user()->id, $validated['recipient_id']);
        }

        $message = Message::create($validated);
        $message->conversation->touch();

        return new JsonResponse(['message' => ['success' => ['Message sent.']]], Response::HTTP_OK);
    }

    private function getConversationId($user_id, $recipient_id) {
        $conversation_id = null;
        $conversation = Conversation::where('user_id', $user_id)
                  ->where('recipient_id', $recipient_id)
                  ->first();
        if(!$conversation) {
            do {
                $conversation_id = random_int(11111, 99999);
            } while (Conversation::where('conversation_id', $conversation_id)->first() != null);
            Conversation::create(['user_id' => $user_id, 'recipient_id' => $recipient_id, 'conversation_id' => $conversation_id]);
            if ($user_id != $recipient_id) Conversation::create(['user_id' => $recipient_id, 'recipient_id' => $user_id, 'conversation_id' => $conversation_id]);
        } else {
            $conversation_id = $conversation->conversation_id;
        }
        return $conversation_id;
    }

    public function markConversationAsRead(Conversation $conversation) {
        foreach ($conversation->messages as $message) {
            if(!$message->read) {
                $message->read = true;
                $message->save([
                    'touch' => false]);
            }
        }
    }
}
