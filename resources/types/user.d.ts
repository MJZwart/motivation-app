import { Task } from "./task"

export type User = {
    id: number,
    username: String,
    admin: boolean,
    email: String,
    first: boolean,
    rewards: String,
    show_achievements: boolean,
    show_reward: boolean,
    show_friends: boolean,
    friends: Array,
}

export type UserStats = {
    repeatable_most_completed: Task,
    tasks_completed: Number,
}

export type BannedUser = {
    id: Number,
    created_at: Date,
    time_since: String,
    updated_at: Date,
    user: {
        username: String,
        id: Number,
    },
    reason: String,
    days: Number,
    admin: {
        username: String,
        id: Number,
    },
    banned_until: Date,
    past: Boolean,
    banned_until_time: String,
    comment: String,
    end_ban: Boolean,
}