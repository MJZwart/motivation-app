import axios from "axios";

export default {

    namespaced: true,

    state: {
        // admin: false,
    },
    mutations: {
        // setAdmin: (state, value) => {
        //     state.admin = value;
        //     localStorage.setItem('admin', JSON.stringify(value));
        // },
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
    },
}