export type Friend = {
    id: number;
    friendship_id: number;
    username: string;
    friends_since: string;
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
