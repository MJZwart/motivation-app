// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        //
    },
    mutations: {
        //
    },
    getters: {
        isAdmin: (state, getters, rootState, rootGetters) => {
            return rootGetters['user/getUser'].admin;
        },
    },
    actions: {
        checkAdmin: () => {
            axios.get('/isadmin');
        },
        fetchBugs: ({commit}) => {
            axios.get('/examples/tasks').then(response => {
                commit('setExampleTasks', response.data.data);
            });
        },
    },
}