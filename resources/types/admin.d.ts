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
};

export type ReportedUser = NewReportedUser & {
    id: number;
    created_at: Date;
    reported_date: Date;
    reported_by_name: string;
    conversation: string;
};
