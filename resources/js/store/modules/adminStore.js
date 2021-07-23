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
        newAchievement: ({commit}, achievement) => {
            return axios.post('/achievements/new', achievement).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                return Promise.resolve();
            });
        },
    },
}