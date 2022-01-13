// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        bugs: {},
    },
    mutations: {
        setBugs(state, bugs) {
            state.bugs = bugs;
        },
    },
    getters: {
        isAdmin: (state, getters, rootState, rootGetters) => {
            return rootGetters['user/getUser'].admin;
        },
        getBugs: state => {
            return state.Bugs;
        },
    },
    actions: {
        checkAdmin: () => {
            axios.get('/isadmin');
        },
        fetchBugs: ({commit}) => {
            //
        },
    },
}