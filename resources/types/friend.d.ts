export type Friend = {
    id: Number,
    created_at: Date,
    updated_at: Date,
    user_id: Number,
    friend_id: Number,
    accepted: Boolean,
}