export type BugReport = {
    id: Number,
    comment: String,
    image_link: String,
    page: String,
    severity: Number,
    title: String,
    type: String,
}

export type Feedback = {
    text: String,
    type: String,
    user_id: Number | null,
    email: String | null,
}