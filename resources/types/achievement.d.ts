export type NewAchievement = {
    description: string;
    image?: null; //TODO implement once images are used in achievements.
    name: string;
    trigger_amount: number;
    trigger_description: string;
    trigger_type: string; //TODO Enum
};

export type Achievement = NewAchievement & {
    id: number | null;
    created_at: string | null;
    earned: string;
    updated_at: string | null;
};

export type AchievementTrigger = {
    trigger_type: string;
    trigger_description: string;
};
