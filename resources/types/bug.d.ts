import {StrippedUser} from './user';

export type NewBugReport = {
    title: string;
    page: string;
    type: string;
    severity: number;
    image_link: string;
    comment: string;
};

export type BugReport = NewBugReport & {
    id: number;
    user: StrippedUser | null;
    status: number;
    admin_comment: string;
};
