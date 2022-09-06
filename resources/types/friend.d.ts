export type Friend = {
    id: number;
    created_at: Date;
    updated_at: Date;
    user_id: number;
    friend_id: number;
    accepted: boolean;
    username: string;
};

export type FriendRequests = {
    outgoing: FriendRequest[];
    incoming: FriendRequest[];
};

export type FriendRequest = {
    id: number;
    friendship_id: number;
    friend: string;
    sent: string;
};
