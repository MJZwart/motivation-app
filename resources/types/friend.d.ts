export type Friend = {
    id: number,
    created_at: Date,
    updated_at: Date,
    user_id: number,
    friend_id: number,
    accepted: boolean,
    username: string,
}