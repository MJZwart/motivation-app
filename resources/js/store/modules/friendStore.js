import axios from "axios";

export default {

    namespaced: true,

    state: {
        
    },
    mutations: {
        
    },
    getters: {
        
    },
    actions: {
        sendRequest: ({commit}, friendId) => {
            axios.post('/friend/request/' + friendId).then(function(response){
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
            });
        },
    }
}