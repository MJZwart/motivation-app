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

        sendMessage: ({dispatch, commit}, message) => {
            return axios.post('/message', message).then(response => {
                commit('setConversations', response.data.data);
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            });
        },

        deleteMessage: ({dispatch, commit}, messageId) => {
            return axios.delete('/message/' + messageId).then(response => {
                commit('setConversations', response.data.data);
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            });
        },

        markConversationRead: (_, conversationId) => {
            axios.put('/conversation/' + conversationId + '/read');
        },

        blockUser: ({dispatch, commit}, userId) => {
            return axios.put('/user/' + userId + '/block').then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('setConversations', response.data.data);
                return Promise.resolve();
            });
        },

    },
}