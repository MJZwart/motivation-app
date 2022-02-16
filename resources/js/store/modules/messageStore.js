// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        conversations: [],
    },
    mutations: {
        setConversations(state, conversations) {
            state.conversations = conversations;
        },
    },
    getters: {
        getConversations(state) {
            return state.conversations;
        },
    },
    actions: {

        getConversations: ({commit}) => {
            return axios.get('/conversations').then(response => {
                commit('setConversations', response.data.data);
                return Promise.resolve();
            });
        },

        sendMessage: ({dispatch}, message) => {
            return axios.post('/message', message).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            });
        },

        markConversationRead: (_, conversationId) => {
            axios.put('/conversation/' + conversationId + '/read');
        },
    },
}