export type Achievement = {
    id: number,
    created_at: String,
    description: String,
    image: null, //TODO implement once images are used in achievements.
    name: String,
    pivot: {
        user_id: number,
        achievement_id: number,
        earned: String,
    },
    trigger_amount: number,
    trigger_description: String,
    trigger_type: String, //TODO Enum
    updated_at: String,
}