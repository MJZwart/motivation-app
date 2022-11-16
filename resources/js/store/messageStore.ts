import axios from 'axios';
import {defineStore} from 'pinia';
import {Conversation, NewMessage} from 'resources/types/message';

export const useMessageStore = defineStore('message', {
    state: () => {
        return {
            conversations: [] as Conversation[],
            notifications: [] as Notification[],
            hasMessages: false,
            hasNotifications: false,
        }
    },
    actions: {
        async getConversations() {
            const {data} = await axios.get('/conversations');
            this.conversations = data.data;
        },
        async hasUnread() {
            const {data} = await axios.get('/unread');
            this.hasNotifications = data.hasNotifications;
            this.hasMessages = data.hasMessages;
        },

        async sendMessage(message: NewMessage) {
            await axios.post('/message', message);
        },

        async deleteMessage(messageId: number) {
            await axios.delete(`/message/${messageId}`);
        },
        async getNotifications() {
            const {data} = await axios.get('/notifications');
            this.notifications = data.data;
        },
        async deleteNotification(notificationId: number) {
            const {data} = await axios.delete(`/notifications/${notificationId}`);
            this.notifications = data.data.notifications;
        },

        markConversationRead(conversationId: number) {
            axios.put(`/conversation/${conversationId}/read`);
        },

        async deleteNotificationAction(notificationId: number) {
            const {data} = await axios.put(`/notifications/${notificationId}/disable-action`);
            this.notifications = data.data;
        },

    },
});