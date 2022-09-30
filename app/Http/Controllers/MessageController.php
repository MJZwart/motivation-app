<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\Friend;
use App\Http\Resources\ConversationOverviewResource;
use App\Http\Requests\SendMessageRequest;
use App\Models\BlockedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
        if ($user->isBlocked($request['recipient_id'])) {
            return ResponseWrapper::errorResponse('You are unable to send messages to this user.');
        }
        $validated = $request->validated();
        $validated['sender_id'] = $user->id;

        if (!array_key_exists('conversation_id', $validated)) {
            $validated['conversation_id'] = $this->getConversationId($user->id, $validated['recipient_id']);
        }

        $message = Message::create($validated);
        $message->conversation->touch();
        ActionTrackingHandler::handleAction($request, 'STORE_MESSAGE', 'Sending message');

        return ResponseWrapper::successResponse('Message sent.');
    }

    private function getConversationId($user_id, $recipient_id)
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
        $this->makeMessageInvisibleToUser($message, $user->id);
        ActionTrackingHandler::handleAction($request, 'DELETE_MESSAGE', 'Deleting message');
        return ResponseWrapper::successResponse('Message deleted.');
    }

    public function blockUser(Request $request, User $blockedUser)
    {
        /** @var User */
        $user = Auth::user();
        BlockedUser::create([
            'user_id' => $user->id,
            'blocked_user_id' => $blockedUser->id,
        ]);
        $this->makeConversationInvisible($user, $blockedUser);
        //delete friendship and inverse friendship
        $toDelete = Friend::where('user_id', $user->id)->where('friend_id', $blockedUser->id)->first();
        if ($toDelete) $toDelete->delete();
        $toDelete = Friend::where('user_id', $blockedUser->id)->where('friend_id', $user->id)->first();
        if ($toDelete) $toDelete->delete();

        ActionTrackingHandler::handleAction($request, 'BLOCK_USER', 'Blocked user ' . $blockedUser->username);
        return ResponseWrapper::successResponse('User blocked.');
    }

    private function makeConversationInvisible($user, $blockedUser)
    {
        $conversation = Conversation::where('user_id', $user->id)->where('recipient_id', $blockedUser->id)->first();
        if (!$conversation) return;
        foreach ($conversation->messages as $message) {
            $this->makeMessageInvisibleToUser($message, $user->id);
        }
    }

    private function makeMessageInvisibleToUser($message, $userId)
    {
        if ($message->recipient_id == $userId) {
            $message->visible_to_recipient = false;
        }
        if ($message->sender_id == $userId) {
            $message->visible_to_sender = false;
        }
        $message->save(['touch' => false]);
    }
}
