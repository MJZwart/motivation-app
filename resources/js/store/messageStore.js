import axios from 'axios';
import {defineStore} from 'pinia';

export const useMessageStore = defineStore('message', {
    state: () => {
        return {
            /** @type Array<import('resources/types/message').Conversation> | null */
            conversations: [],
            /** @type Array<import('resources/types/notification').Notification> | null */
            notifications: null,
            /** @type Boolean */
            hasMessages: false,
            /** @type Boolean */
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

        /**
         * @param {import('resources/types/message').Message} message
         */
        async sendMessage(message) {
            await axios.post('/message', message);
        },

        /**
         * @param {Number} messageId
         */
        async deleteMessage(messageId) {
            await axios.delete(`/message/${messageId}`);
        },
        async getNotifications() {
            const {data} = await axios.get('/notifications');
            this.notifications = data.data;
        },
        /**
         * @param {Number} notificationId
         */
        async deleteNotification(notificationId) {
            const {data} = await axios.delete(`/notifications/${notificationId}`);
            this.notifications = data.data;
        },

        /**
         * @param {Number} conversationId
         */
        markConversationRead(conversationId) {
            axios.put(`/conversation/${conversationId}/read`);
        },

        /**
         * @param {Number} notificationId
         */
        async deleteNotificationAction(notificationId) {
            const {data} = await axios.put(`/notifications/${notificationId}/disable-action`);
            this.notifications = data.data;
        },

    },
});