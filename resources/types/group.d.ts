import {StrippedUser} from './user';

export type Group = NewGroup & {
    id: number;
    time_created: Date;
    time_updated: Date;
    members: GroupUser[];
};

export type NewGroup = {
    name: string;
    description: string;
    is_public: boolean;
    require_application: boolean;
};

export type GroupPage = Group & {
    admin: StrippedUser;
    is_member: boolean;
    is_admin: boolean;
    rank: Rank;
    joined: string;
    has_application: boolean;
    invites: number[] | null;
};

export type GroupUser = {
    id: number;
    username: string;
    rank: Rank;
    joined: Date;
};

export type Rank = {
    name: string;
    can_delete: boolean;
    can_edit: boolean;
    can_manage_members: boolean;
    can_moderate_messages: boolean;
    owner: boolean;
    member: boolean;
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
}