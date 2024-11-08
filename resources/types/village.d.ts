export type Village = {
    id: number;
    name: string;
    active: boolean;
    level: number;
    experience: number;
    coins: number;
    level_exp_needed: number;
    economy: number;
    economy_exp: number;
    economy_exp_needed: number;
    labour: number;
    labour_exp: number;
    labour_exp_needed: number;
    craft: number;
    craft_exp: number;
    craft_exp_needed: number;
    art: number;
    art_exp: number;
    art_exp_needed: number;
    community: number;
    community_exp: number;
    community_exp_needed: number;
};

export type ChangeReward = {
    rewards: number;
    keepOldInstance: string | number | null;
    newVillageName?: string | null;
};

export type Coins = {
    platinum?: string | null;
    gold?: string | null;
    silver?: string | null;
    bronze: string | null;
};