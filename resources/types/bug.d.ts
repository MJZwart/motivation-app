export type BugReport = {
    id: number,
    user_id: number,
    comment: string,
    image_link: string,
    page: string,
    severity: number,
    title: string,
    type: string,
    status: number,
    admin_comment: string,
}

export type Feedback = {
    text: string,
    type: string,
    user_id?: string | number,
    email?: string,
}