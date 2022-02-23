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
        getAdminDashboard: ({commit}) => {
            axios.get('/admin/dashboard').then(response => {
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                commit('achievement/setAchievementTriggers', response.data.achievementTriggers, {root:true});
                commit('bugReport/setBugReports', response.data.bugReports, {root:true});
            });
        },
        sendNotification: ({dispatch}, notification) => {
            axios.post('/notifications/all', notification).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },
    },
}