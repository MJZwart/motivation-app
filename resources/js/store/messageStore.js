// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';

export const useMessageStore = defineStore('reward', {
    state: () => {
        return {
            conversations: [],
            hasMessages: false,
        }
    },
    actions: {
        async getConversations() {
            const data = await axios.get('/conversations')
            this.conversations = data.data;
        },

        async sendMessage(message) {
            const data = await  axios.post('/message', message)
            this.conversations = data.data;
            this.addToast(data.message);
        },

        async deleteMessage(messageId) {
            const data = await  axios.delete('/message/' + messageId)
            this.conversations = data.data;
            this.addToast(data.message);
        },

        markConversationRead(conversationId) {
            axios.put('/conversation/' + conversationId + '/read');
        },

        addToast(toast) {
            const mainStore = useMainStore();
            mainStore.addToast(toast);
        },

    },
})