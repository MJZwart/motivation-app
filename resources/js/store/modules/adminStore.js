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
        newAchievement: ({commit, dispatch}, achievement) => {
            return axios.post('/achievements', achievement).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                return Promise.resolve();
            });
        },
        editAchievement: ({commit, dispatch}, achievement) => {
            return axios.put('/achievements/' + achievement.id, achievement).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('achievement/setAchievements', response.data.achievements, {root:true});
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