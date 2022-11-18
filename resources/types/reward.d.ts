export type Reward = {
    id: number;
    name: string;
    active: boolean;
    rewardType: string;
    level: number;
    experience: number;
    coins: number;
    level_exp_needed: number;
    a: number;
    a_exp: number;
    a_exp_needed: number;
    b: number;
    b_exp: number;
    b_exp_needed: number;
    c: number;
    c_exp: number;
    c_exp_needed: number;
    d: number;
    d_exp: number;
    d_exp_needed: number;
    e: number;
    e_exp: number;
    e_exp_needed: number;
};

export type ChangeReward = {
    rewards: string;
    // TODO change 'rewards' to 'rewardType', it's a little ambiguous
    keepOldInstance: string | number | null;
    // TODO And while you're at it, either pick camel case or underscore
    new_object_name?: string | null;
};

export type Coins = {
    platinum?: string | null;
    gold?: string | null;
    silver?: string | null;
    bronze: string | null;
};