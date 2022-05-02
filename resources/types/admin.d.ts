export type ExperiencePoint = {
    level: Number,
    experience_points: Number,
}

export type CharExpGain = {
    id: Number,
    task_type: String,
    strength: Number,
    agility: Number,
    endurance: Number,
    intelligence: Number,
    charisma: Number,
    level: Number,
}

export type VillageExpGain = {
    id: Number,
    task_type: String,
    economy: Number,
    labour: Number,
    craft: Number,
    art: Number,
    community: Number,
    level: Number,
}

export type ReportedUser = {
    id: Number,
    created_at: Date,
    reported_user_id: Number,
    reported_by_user_id: Number,
    comment: String,
    reason: String,
    conversation_id: Number | null,
}