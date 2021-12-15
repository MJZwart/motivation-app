import axios from "axios";

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
        newAchievement: ({commit}, achievement) => {
            return axios.post('/achievements', achievement).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                return Promise.resolve();
            });
        },
        editAchievement: ({commit}, achievement) => {
            return axios.put('/achievements/' + achievement.id, achievement).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                return Promise.resolve();
            });
        },
        sendNotification: ({commit}, notification) => {
            axios.post('/notifications/all', notification).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },
    },
}