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