import {StrippedUser} from './user';

export type ExperiencePoint = {
    level: number;
    experience_points: number;
};

export type CharExpGain = {
    id: number;
    task_type: string;
    strength: number;
    agility: number;
    endurance: number;
    intelligence: number;
    charisma: number;
    level: number;
};

export type VillageExpGain = {
    id: number;
    task_type: string;
    economy: number;
    labour: number;
    craft: number;
    art: number;
    community: number;
    level: number;
};

export type NewReportedUser = {
    comment: string;
    reason: string;
    conversation_id?: string;
    group_id?: number;
};

export type ReportedUser = NewReportedUser & {
    id: number;
    created_at: Date;
    reported_date: Date;
    reported_by_name: string;
    conversation: string;
    group_id?: number;
};

export type Overview = {
    'total-users': {
        'total': number;
        'guests': number;
    };
    'new-users': {
        'total': number;
        'guests': number;
    }
    'active-users': {
        'total': number;
        'guests': number;
    }
    'unarchived-feedback': number;
    'unresolved-bugs': number;
    'new-feedback': number;
    'new-bugs': number;
    'new-user-reports': number;
}

export type ActionType = {
    action_type: string;
}
export type ActionFilters = {
    types: string[],
    users: StrippedUser[],
    minDate: Date[],
}
export type Actions = { 
    action: string,
    action_type: string,
    error?: string,
    timestamp: string,
    user: StrippedUser,
    user_agent: string,
}

export type GlobalSetting = {
    key: string,
    value: string,
}

export type MaintenanceBannerMessage = NewMaintenanceBannerMessage & {
    id: number;
    created_at: string;
    updated_at: string;
}

export type NewMaintenanceBannerMessage = {
    message: string;
    starts_at: string;
    ends_at: string;
}