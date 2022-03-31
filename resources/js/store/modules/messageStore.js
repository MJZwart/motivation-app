// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        conversations: [],
        hasMessages: false,
    },
    mutations: {
        setConversations(state, conversations) {
            state.conversations = conversations;
        },
        setHasMessages(state, bool) {
            state.hasMessages = bool;
        },
    },
    getters: {
        getConversations(state) {
            return state.conversations;
        },
        getHasMessages(state) {
            return state.hasMessages;
        },
    },
    actions: {

        getConversations: ({commit}) => {
            return axios.get('/conversations').then(response => {
                commit('setConversations', response.data.data);
                return Promise.resolve();
            });
        },

        sendMessage: ({commit}, message) => {
            return axios.post('/message', message).then(response => {
                commit('setConversations', response.data.data);
                commit('addToast', response.data.message, {root:true});
                return Promise.resolve();
            });
        },

        deleteMessage: ({commit}, messageId) => {
            return axios.delete('/message/' + messageId).then(response => {
                commit('setConversations', response.data.data);
                commit('addToast', response.data.message, {root:true});
                return Promise.resolve();
            });
        },

        markConversationRead: (_, conversationId) => {
            axios.put('/conversation/' + conversationId + '/read');
        },

    },
}