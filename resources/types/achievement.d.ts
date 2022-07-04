export type Achievement = {
    id: number,
    created_at: string,
    description: string,
    image: null, //TODO implement once images are used in achievements.
    name: string,
    pivot: {
        user_id: number,
        achievement_id: number,
        earned: string,
    },
    trigger_amount: number,
    trigger_description: string,
    trigger_type: string, //TODO Enum
    updated_at: string,
}