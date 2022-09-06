export type Achievement = {
    id: number | null;
    created_at: string | null;
    description: string;
    image: null; //TODO implement once images are used in achievements.
    name: string;
    earned: string;
    trigger_amount: number;
    trigger_description: string;
    trigger_type: string; //TODO Enum
    updated_at: string | null;
};

export type AchievementTrigger = {
    trigger_type: string;
    trigger_description: string;
};
