export type Group = {
    id: Number,
    created_at: Date,
    updated_at: Date,
    name: String,
    description: String,
    is_public: Boolean,
    members: Array<GroupUser>,
}

export type GroupUser = {
    id: Number,
    username: String,
    rank: String,
    joined: Date,
}