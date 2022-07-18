export type Notification = {
    id: number | null,
    read: boolean | null,
    created_at: Date | null,
    title: string,
    text: string,
    link: string | null
    link_text: string | null,
}