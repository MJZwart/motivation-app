// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        requests: null,
    },
    mutations: {
        setRequests(state, value) {
            state.requests = value;
        },
    },
    getters: {
        getRequests(state) {
            return state.requests;
        },
    },
    actions: {
        sendRequest: ({dispatch}, friendId) => {
            axios.post('/friend/request/' + friendId).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },
        getRequests: ({commit}) => {
            axios.get('/friend/requests/all').then(response => {
                commit('setRequests', response.data);
            });
        },
        acceptRequest: ({commit, dispatch}, requestId) => {
            axios.post('/friend/request/' + requestId + '/accept').then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('user/setUser', response.data.user, {root:true});
                commit('setRequests', response.data.requests);
            });
        },
        denyRequest: ({commit, dispatch}, requestId) => {
            axios.post('/friend/request/' + requestId + '/deny').then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('setRequests', response.data.requests);
            });
        },
        removeFriend: ({commit, dispatch}, friendId) => {
            axios.delete('/friend/remove/' + friendId).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('user/setUser', response.data.user, {root:true});
            });
        },
    },
}