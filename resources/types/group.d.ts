import {StrippedUser} from './user';

export type Group = {
    id: number;
    time_created: Date;
    time_updated: Date;
    name: string;
    description: string;
    is_public: boolean;
    require_application: boolean;
    members: GroupUser[];
};

export type GroupPage = {
    id: number;
    time_created: Date;
    time_updated: Date;
    name: string;
    description: string;
    is_public: boolean;
    require_application: boolean;
    members: GroupUser[];
    admin: StrippedUser;
    is_member: boolean;
    rank: string;
    joined: string;
    has_application: boolean;
    invites: number[] | null;
};

export type GroupUser = {
    id: number;
    username: string;
    rank: string;
    joined: Date;
};

export type Application = {
    id: number;
    applied_at: Date;
    user_id: number;
    username: string;
    group_id: number;
};
