import axios from "axios";

export default {

    namespaced: true,

    state: {
        requests: null,
    },
    mutations: {
        setRequests(state, value){
            state.requests = value;
        },
    },
    getters: {
        getRequests(state){
            return state.requests;
        },
    },
    actions: {
        sendRequest: ({commit}, friendId) => {
            axios.post('/friend/request/' + friendId).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
            });
        },
        getRequests: ({commit}) => {
            axios.get('/friend/requests/all').then(response => {
                commit('setRequests', response.data);
            });
        },
        acceptRequest: ({commit}, requestId) => {
            axios.post('/friend/request/' + requestId + '/accept').then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                commit('user/setUser', response.data.user, {root:true});
                commit('setRequests', response.data.requests);
            });
        },
        denyRequest: ({commit}, requestId) => {
            axios.post('/friend/request/' + requestId + '/deny').then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                commit('setRequests', response.data.requests);
            });
        },
    }
}