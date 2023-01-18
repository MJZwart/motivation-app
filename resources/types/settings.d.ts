export type PasswordSettings = {
    password: string;
    old_password: string;
    password_confirmation: string;
};

export type ProfileSettings = {
    show_achievements: boolean;
    show_friends: boolean;
    show_reward: boolean;
    show_timeline: boolean;
};

export type EmailSettings = {
    email: string;
};
