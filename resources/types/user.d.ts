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
    friends: Friend[];
};

export type UserStats = {
    repeatable_most_completed: Task;
    tasks_completed: number;
};

export type BannedUser = {
    id: number;
    created_at: Date;
    time_since: string;
    updated_at: Date;
    user: {
        username: string;
        id: number;
    };
    reason: string;
    days: number;
    admin: {
        username: string;
        id: number;
    };
    banned_until: string;
    past: boolean;
    banned_until_time: string;
    early_release: Date;
    ban_edit_comment: string;
    end_ban?: boolean;
    ban_edit_log: string;
};

export type NewSuspension = {
    reason: string;
    days: number | null;
    indefinite: boolean;
    close_reports: boolean;
};

export type StrippedUser = {
    id: number;
    username: string;
};

export type UserToBan = {
    id: number;
    last_report_date: Date | string;
    banned: Array<BannedUser> | null;
    banned_until: string | null;
    report_amount: number;
    reports: ReportedUser[];
    username: string;
};

export type UserProfile = {
    id: number;
    created_at: string;
    username: string;
    display_picture?: string | null;
    rewardObj?: Reward | null;
    achievements?: Achievement[];
    friends?: Friend[];
    suspended?: {
        until: Date;
        reason: string;
    } | null;
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