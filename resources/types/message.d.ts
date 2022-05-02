export type Conversation = {
    id: Number,
    created_at: Date,
    updated_at: Date,
    user_id: Number,
    recipient_id: Number,
    conversation_id: Number,
}

export type Message = {
    id: Number,
    created_at: Date,
    updated_at: Date,
    sender_id: Number,
    recipient_id: Number,
    message: String,
    conversation_id: Number,
    read: Boolean,
    visible_to_sender: Boolean,
    visible_to_recipient: Boolean,
}