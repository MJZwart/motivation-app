export type Notification = NewNotification & {
    id: number;
    read: boolean | null;
    created_at: Date | null;
    links: NotificationLink[] | null;
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

export type NewNotification = {
    title: string;
    text: string;
    link?: string | null;
    link_text?: string | null;
}
