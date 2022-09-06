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

export type ReportedUser = {
    id: number;
    created_at: Date;
    reported_user_id: number;
    reported_by_user_id: number;
    comment: string;
    reason: string;
    conversation_id: number | null;
};

export type NewReportedUser = {
    comment: string;
    reason: string;
    conversation_id?: string;
};
