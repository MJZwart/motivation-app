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
        isAdmin: (rootState) => {
            return rootState.user.admin;
        },
    },
    actions: {
        // checkAdmin: ({commit}) => {
        //     axios.get('/isadmin').then(response => {
        //         commit('setAdmin', response.data);
        //     });
        // },
    }
}