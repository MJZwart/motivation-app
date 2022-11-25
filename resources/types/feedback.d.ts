import {Diagnostics} from './global';

export type NewFeedback = {
    type: string;
    text: string;
    email?: string;
    user_id?: number;
    diagnostics_approval: boolean;
    diagnostics?: Diagnostics | string;
};

export type Feedback = NewFeedback & {
    id: number;
    created_at: Date;
    archived: boolean;
    user?: {
        id: number;
        username: string;
    };
};
