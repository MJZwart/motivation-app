import {Task} from './task';
import {Reward} from './reward';
import {Achievement} from './achievement';
import {Friend} from './friend';
import {ReportedUser} from './admin';

export type User = {
    id: number;
    username: string;
    admin?: boolean;
    email: string;
    first: boolean;
    rewards: string;
    show_achievements: boolean;
    show_reward: boolean;
    show_friends: boolean;
    show_timeline: boolean;
    show_tutorial: boolean;
    friends: Friend[];
    language: string;
    guest: boolean;
};

export type UserStats = {
    repeatable_most_completed: Task;
    tasks_completed: number;
};

export type SuspendedUser = {
    id: number;
    created_at: string;
    time_since: string;
    updated_at: Date;
    username: string;
    user_id: number;
    reason: string;
    days: number;
    admin_username: string;
    admin_id: number;
    suspended_until: string;
    past: boolean;
    suspended_until_time: string;
    early_release: Date;
    suspension_edit_comment: string;
    end_suspension?: boolean;
    suspension_edit_log: string;
};

export type NewSuspension = {
    reason: string;
    days: number | null;
    indefinite: boolean;
    close_reports: boolean;
    user_id: number;
};

export type StrippedUser = {
    id: number;
    username: string;
};

export type UserToSuspend = {
    id: number;
    last_report_date: Date | string;
    suspended: Array<SuspendedUser> | null;
    suspended_until: string | null;
    report_amount: number;
    reports: ReportedUser[];
    username: string;
};

export type SuspensionLog = {
    date: string,
    log: string,
    comment: string,
}

export type UserProfile = {
    id: number;
    created_at: string;
    username: string;
    display_picture?: string | null;
    rewardObj?: Reward | null;
    achievements?: Achievement[];
    friends?: Friend[];
    timeline?: boolean;
    suspended?: {
        until: Date;
        reason: string;
    } | null;
    guest: boolean;
};

export type Login = {
    username: string;
    password: string;
};

export type Register = {
    username: string;
    password: string;
    password_confirmation: string;
    email: string;
    agree_to_tos: boolean;
    language?: string;
};

export type NewUser = {
    rewardsType: string;
    tasks: Task[];
    reward_object_name: string | null;
};

export type Blocked = {
    id: number;
    blocked_user_id: number;
    blocked_user: string;
    created_at: Date;
};

export type ResetPassword = {
    email: string;
    password: string;
    password_confirmation: string;
    token: string;
};