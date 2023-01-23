import axios from 'axios';
import {defineStore} from 'pinia';
import {NewMessage} from 'resources/types/message';

export const useMessageStore = defineStore('message', {
    state: () => {
        return {
            hasMessages: false,
            hasNotifications: false,
        }
    },
    actions: {
        async hasUnread() {
            const {data} = await axios.get('/message/unread');
            this.hasNotifications = data.hasNotifications;
            this.hasMessages = data.hasMessages;
        },
        async getConversations() {
            const {data} = await axios.get('/message/conversations');
            return data.data;
        },
        async sendMessage(message: NewMessage) {
            await axios.post('/message', message);
        },
        async deleteMessage(messageId: number) {
            await axios.delete(`/message/${messageId}`);
        },
        markConversationRead(conversationId: number) {
            axios.put(`/message/conversation/${conversationId}/read`);
        },

        async getNotifications() {
            const {data} = await axios.get('/notifications');
            return data.data;
        },
        async deleteNotification(notificationId: number) {
            const {data} = await axios.delete(`/notifications/${notificationId}`);
            return data.data.notifications;
        },
        async deleteNotificationAction(notificationId: number) {
            const {data} = await axios.put(`/notifications/${notificationId}/disable-action`);
            return data.data;
        },
    },
});