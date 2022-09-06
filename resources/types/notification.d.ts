export type Notification = {
    id: number;
    read: boolean | null;
    created_at: Date | null;
    title: string;
    text: string;
    links: NotificationLink[] | null;
    link_text: string | null;
    link_active: boolean | null;
    delete_links_on_action: boolean | null;
};

export type NotificationLink = {
    link: {
        api: string;
        url: string;
    };
    text: string;
};
