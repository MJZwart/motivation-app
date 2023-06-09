import {StrippedUser} from './user';

export type Group = NewGroup & {
    id: number;
    time_created: Date;
    time_updated: Date;
    members: GroupUser[];
    is_member: boolean;
    rank: Rank | null;
    joined?: string;
    level: number;
};

export type NewGroup = {
    name: string;
    description: string;
    is_public: boolean;
    require_application: boolean;
};

export type GroupPage = Group & {
    admin: StrippedUser;
    rank: Rank;
    joined: string;
    has_application: boolean;
    invites: number[] | null;
    experience: number;
    exp_to_next_level: number;
};

export type GroupUser = {
    id: number;
    user_id: number;
    username: string;
    rank: Rank;
    joined: Date;
};

export type Rank = {
    id: number;
    name: string;
    can_edit: boolean;
    can_manage_members: boolean;
    can_moderate_messages: boolean;
    owner: boolean;
    member: boolean;
    position: number;
}

export type Application = {
    id: number;
    applied_at: Date;
    user_id: number;
    username: string;
    group_id: number;
};

export type NewGroupMessage = {
    message: string;
}

export type GroupMessage = NewGroupMessage & {
    id: number;
    user: StrippedUser;
    created_at: Date;
    group_id: number;
}