// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        // messages: [],
        conversations: [],
    },
    mutations: {
        // setMessages(state, messages) {
        //     state.messages = messages;
        // },
        setConversations(state, conversations) {
            state.conversations = conversations;
        },
    },
    getters: {
        // getMessages(state) {
        //     return state.messages;
        // },
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
    },
}