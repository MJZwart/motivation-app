export type BugReport = {
    id: number,
    comment: string,
    image_link: string,
    page: string,
    severity: number,
    title: string,
    type: string,
}

export type Feedback = {
    text: string,
    type: string,
    user_id: number | null,
    email: string | null,
}