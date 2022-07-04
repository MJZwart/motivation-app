export type Conversation = {
    id: number,
    created_at: Date,
    updated_at: Date,
    user_id: number,
    recipient_id: number,
    conversation_id: number,
}

export type Message = {
    id: number,
    created_at: Date,
    updated_at: Date,
    sender_id: number,
    recipient_id: number,
    message: string,
    conversation_id: number,
    read: boolean,
    visible_to_sender: boolean,
    visible_to_recipient: boolean,
}