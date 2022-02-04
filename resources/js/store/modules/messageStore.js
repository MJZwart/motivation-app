// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        messages: [],
        conversations: [],
    },
    mutations: {
        setMessages(state, messages) {
            state.messages = messages;
        },
        setConversations(state, conversations) {
            state.conversations = conversations;
        },
    },
    getters: {
        getMessages(state) {
            return state.messages;
        },
        getConversations(state) {
            return state.conversations;
        },
    },
    actions: {
        getMessages: ({commit}) => {
            axios.get('/messages').then(response => {
                commit('setMessages', response.data.data);
            });
        },
        getConversations: ({commit}) => {
            axios.get('/conversations').then(response => {
                commit('setConversations', response.data.data);
            });
        },
    },
}