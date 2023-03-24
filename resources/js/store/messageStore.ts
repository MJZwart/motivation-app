import axios from 'axios';
import {defineStore} from 'pinia';
import {NewMessage} from 'resources/types/message';
import {useUserStore} from './userStore';

export const useMessageStore = defineStore('message', {
    state: () => {
        return {
            hasMessages: false,
            hasNotifications: false,
        }
    },
    actions: {
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
            const userStore = useUserStore();
            userStore.getUnread();
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