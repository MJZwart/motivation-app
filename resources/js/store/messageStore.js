// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';

export const useMessageStore = defineStore('message', {
    state: () => {
        return {
            conversations: [],
            hasMessages: false,
            notifications: null,
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

        async sendMessage(message) {
            const {data} = await axios.post('/message', message);
            this.conversations = data.data;
        },

        async deleteMessage(messageId) {
            const {data} = await axios.delete('/message/' + messageId);
            this.conversations = data.data;
        },
        async getNotifications() {
            const {data} = await axios.get('/notifications');
            this.notifications = data.data;
        },
        async deleteNotification(notificationId) {
            const {data} = await axios.delete('/notifications/' + notificationId);
            this.notifications = data.data;
        },

        markConversationRead(conversationId) {
            axios.put('/conversation/' + conversationId + '/read');
        },
    },
});