export interface NewBugReport {
    title: string;
    page: string;
    type: string;
    severity: number;
    image_link: string;
    comment: string;
}

export interface BugReport {
    id: number;
    user_id: number;
    status: number;
    admin_comment: string;
}
