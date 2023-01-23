export type NewBugReport = {
    title: string;
    page: string;
    type: string;
    severity: number;
    image_link: string;
    comment: string;
    diagnostics_approval: boolean,
    diagnostics?: string,
};

export type BugReport = NewBugReport & {
    id: number;
    user_id: number;
    username: string;
    status: number;
    admin_comment: string;
    sendMessageToReporter?: boolean;
};
