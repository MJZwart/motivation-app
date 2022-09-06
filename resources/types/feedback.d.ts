export type Feedback = {
    id: number;
    created_at: Date;
    type: string;
    text: string;
    email: string;
    user: {
        id: number;
        username: string;
    };
    archived: boolean;
};
