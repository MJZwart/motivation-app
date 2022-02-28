// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        balancing: null,
    },
    mutations: {
        setBalancing(state, balancing) {
            state.balancing = balancing;
        },
    },
    getters: {
        isAdmin: (state, getters, rootState, rootGetters) => {
            return rootGetters['user/getUser'].admin;
        },
        getBalancing: state => {
            return state.balancing;
        },
    },
    actions: {
        checkAdmin: () => {
            axios.get('/isadmin');
        },
        getAdminDashboard: ({commit}) => {
            return axios.get('/admin/dashboard').then(response => {
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                commit('achievement/setAchievementTriggers', response.data.achievementTriggers, {root:true});
                commit('bugReport/setBugReports', response.data.bugReports, {root:true});
                commit('setBalancing', response.data.balancing);
                return Promise.resolve();
            });
        },
        sendNotification: ({dispatch}, notification) => {
            axios.post('/notifications/all', notification).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },
    },
}