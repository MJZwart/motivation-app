import {Task} from './task'

export type User = {
    id: number,
    username: string,
    admin: boolean,
    email: string,
    first: boolean,
    rewards: string,
    show_achievements: boolean,
    show_reward: boolean,
    show_friends: boolean,
    friends: Array,
}

export type UserStats = {
    repeatable_most_completed: Task,
    tasks_completed: number,
}

export type BannedUser = {
    id: number,
    created_at: string,
    time_since: string,
    updated_at: string,
    user: {
        username: string,
        id: number,
    },
    reason: string,
    days: number,
    admin: {
        username: string,
        id: number,
    },
    banned_until: string,
    past: boolean,
    banned_until_time: string,
    comment: string,
    end_ban: boolean,
    log: string,
}

export type NewSuspension = {
    reason: string,
    days: number | null,
    indefinite: boolean,
}

export type StrippedUser = {
    id: number,
    username: string,
}