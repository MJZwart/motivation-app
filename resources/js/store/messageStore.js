// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';

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
            console.log(data);
            this.conversations = data.data;
        },
        async hasUnread() {
            const {data} = await axios.get('/unread');
            this.hasNotifications = data.hasNotifications;
            this.hasMessages = data.hasMessages;
        },

        async sendMessage(message) {
            const {data} = await axios.post('/message', message);
            console.log(data);
            this.conversations = data.data;
            this.addToast(data.message);
        },

        async deleteMessage(messageId) {
            const {data} = await axios.delete('/message/' + messageId);
            console.log(data);
            this.conversations = data.data;
            this.addToast(data.message);
        },
        async getNotifications() {
            const {data} = await axios.get('/notifications');
            console.log(data);
            this.notifications = data.data;
        },
        async deleteNotification(notificationId) {
            const {data} = await axios.delete('/notifications/' + notificationId);
            console.log(data);
            this.addToast = data.message;
            this.notifications = data.data;
        },

        markConversationRead(conversationId) {
            axios.put('/conversation/' + conversationId + '/read');
        },

        addToast(toast) {
            const mainStore = useMainStore();
            mainStore.addToast(toast);
        },

    },
});