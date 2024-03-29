export type Conversation = {
    id: number;
    updated_at: Date;
    recipient: {
        id: number;
        username: string;
    };
    conversation_id: string;
    messages: Message[];
    last_message: Message;
    is_blocked: boolean;
};

export type Message = {
    id: number;
    created_at: Date;
    sender: {
        id: number | string;
        username: string;
    } | null;
    recipient: {
        id: number | string;
        username: string;
    } | null;
    message: string;
    read: boolean;
    visible: boolean;
    sent_by_user: boolean;
};

export type NewMessage = {
    recipient_id?: number;
    message: string;
    conversation_id?: string;
};

export type ReportedMessage = {
    id: number;
    created_at: Date;
    sender_id: number;
    sender_username: string;
    message: string;
};

export type ReportedConversation = {
    id: string;
    messages: ReportedMessage[];
};
