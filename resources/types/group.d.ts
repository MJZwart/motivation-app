export type Group = {
    id: number,
    created_at: Date,
    updated_at: Date,
    name: string,
    description: string,
    is_public: boolean,
    members: Array<GroupUser>,
}

export type GroupUser = {
    id: number,
    username: string,
    rank: string,
    joined: Date,
}

export type Application = {
    id: number,
    applied_at: Date,
    user_id: number,
    username: string,
    group_id: number,
}