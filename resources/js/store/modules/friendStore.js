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
        sendRequest: ({commit}, friendId) => {
            return axios.post('/friend/request/' + friendId).then(response => {
                commit('addToast', response.data.message, {root:true});
                return Promise.resolve();
            });
        },
        getRequests: ({commit}) => {
            return axios.get('/friend/requests/all').then(response => {
                commit('setRequests', response.data);
                return Promise.resolve();
            });
        },
        acceptRequest: ({commit}, requestId) => {
            axios.post('/friend/request/' + requestId + '/accept').then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('user/setUser', response.data.user, {root:true});
                commit('setRequests', response.data.requests);
            });
        },
        denyRequest: ({commit}, requestId) => {
            axios.post('/friend/request/' + requestId + '/deny').then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('setRequests', response.data.requests);
            });
        },
        removeRequest: ({commit}, requestId) => {
            axios.delete('/friend/request/' + requestId).then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('setRequests', response.data.requests);
            });
        },
        removeFriend: ({commit}, friendId) => {
            axios.delete('/friend/remove/' + friendId).then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('user/setUser', response.data.user, {root:true});
            });
        },
    },
}