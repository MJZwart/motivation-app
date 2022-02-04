<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Http\Resources\ConversationOverviewResource;

class MessageController extends Controller
{
    public function getMessages() {

    }

    public function getConversations() {
        return ConversationOverviewResource::collection(Conversation::where('user_id', Auth::user()->id)->get());
    }
}
