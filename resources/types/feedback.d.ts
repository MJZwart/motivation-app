export interface NewFeedback {
    type: string;
    text: string;
    email?: string;
    user?: {
        id: number;
        username: string;
    };
}

export interface Feedback extends NewFeedback {
    id: number;
    created_at: Date;
    archived: boolean;
}
