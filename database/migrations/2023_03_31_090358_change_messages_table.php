<?php

use App\Models\Message;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->softDeletes();
        });
        $allMessages = Message::get();
        foreach ($allMessages as $message) {
            $message->user_id = $message->sender_id;
            $message->save(['touch' => false]);
            if (!$message->visible_to_sender) $message->delete();
            $secondMessage = Message::create([
                'user_id' => $message->recipient_id,
                'sender_id' => $message->sender_id,
                'recipient_id' => $message->recipient_id,
                'message' => $message->message,
                'conversation_id' => $message->conversation_id,
                'created_at' => $message->created_at,
                'updated_at' => $message->updated_at,
                'read' => $message->read,
            ]);
            if (!$message->visible_to_recipient) $secondMessage->delete();
        }
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('visible_to_sender');
            $table->dropColumn('visible_to_recipient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('visible_to_sender')->default(true);
            $table->boolean('visible_to_recipient')->default(true);
        });
        $allMessages = Message::get();
        foreach ($allMessages as $message) {
            if ($message->user_id !== $message->sender_id) continue;
            $secondMessage = Message::withTrashed()
                                    ->where('sender_id', $message->sender_id)
                                    ->where('message', $message->message)
                                    ->where('created_at', $message->created_at)
                                    ->first();
            if ($secondMessage->trashed()) $message->visible_to_recipient = false;
            if ($message->trashed()) $message->visible_to_sender = false;
            $message->save(['touch' => false]);
            $secondMessage->forceDelete();
        }
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('user_id')->default(true);
            $table->dropSoftDeletes();
        });
    }
};
