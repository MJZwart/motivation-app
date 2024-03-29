<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Http\Resources\ConversationOverviewResource;
use App\Http\Requests\SendMessageRequest;
use App\Models\BlockedUser;
use Illuminate\Broadcasting\BroadcastException;

class MessageController extends Controller
{

    public function getConversations()
    {
        /** @var User */
        $user = Auth::user();
        return ConversationOverviewResource::collection($user->getVisibleConversations());
    }

    public function sendMessage(SendMessageRequest $request)
    {
        /** @var User */
        $user = Auth::user();
        if ($user->isBlockedByUser($request['recipient_id'])) {
            return ResponseWrapper::errorResponse(__('messages.message.unable_to_send'));
        }
        if ($user->hasBlockedUser($request['recipient_id'])) {
            return ResponseWrapper::errorResponse(__('messages.message.blocked_user'));
        }
        $validated = $request->validated();
        $validated['sender_id'] = $user->id;

        if (!array_key_exists('conversation_id', $validated)) {
            $validated['conversation_id'] = $this->getConversationId($user->id, $validated['recipient_id']);
        }

        Message::createNewMessage($validated);

        try {
            NewMessage::broadcast($request['recipient_id']);
        } catch (BroadcastException $e) {
            error_log('Error broadcasting message: ' . $e->getMessage());
        }

        ActionTrackingHandler::registerAction($request, 'STORE_MESSAGE', 'Sending message');

        return ResponseWrapper::successResponse(__('messages.message.sent'));
    }

    private function getConversationId(int $user_id, int $recipient_id)
    {
        $conversation_id = null;
        $conversation = Conversation::where('user_id', $user_id)
            ->where('recipient_id', $recipient_id)
            ->first();
        if (!$conversation) {
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

    public function markConversationAsRead(Conversation $conversation)
    {
        foreach ($conversation->messages as $message) {
            if (!$message->read) {
                $message->read = true;
                $message->save([
                    'touch' => false
                ]);
            }
        }
    }

    public function deleteMessage(Request $request, Message $message)
    {
        /** @var User */
        $user = Auth::user();
        $message->delete();
        ActionTrackingHandler::registerAction($request, 'DELETE_MESSAGE', 'Deleting message');
        return ResponseWrapper::successResponse(__('messages.message.deleted'));
    }

    public static function makeConversationInvisible(User $user, BlockedUser $blockedUser)
    {
        $conversation = Conversation::where('user_id', $user->id)->where('recipient_id', $blockedUser->id)->first();
        if (!$conversation) return;
        foreach ($conversation->getMessages() as $message) {
            $message->delete();
        }
    }
    public static function restoreHiddenConversation(User $user, BlockedUser $blockedUser)
    {
        $conversation = Conversation::where('user_id', $user->id)->where('recipient_id', $blockedUser->blocked_user_id)->first();
        if (!$conversation) return;
        foreach ($conversation->getTrashedMessages() as $message) {
            $message->restore(['touch' => false]);
        }
    }
}
