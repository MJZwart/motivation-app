export type Feedback = {
    id: Number,
    created_at: Date,
    type: String,
    text: String,
    email: String,
    user: {
        id: Number,
        username: String,
    },
    archived: Boolean
}