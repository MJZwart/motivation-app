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
            axios.post('/friend/request/' + friendId).then(function(response){
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
            });
        },
        getRequests: ({commit}) => {
            axios.get('/friend/requests/all').then(function(response){
                commit('setRequests', response.data);
            });
        },
    }
}