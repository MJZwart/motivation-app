import axios from "axios";

export default {

    namespaced: true,

    state: {
        achievementsByUser: null,
        achievements: null,
    },
    mutations: {
        setAchievements: (state, achievements) => {
            state.achievements = achievements;
        },
        setAchievementsByUser: (state, achievements) => {
            state.achievements = achievements;
        },
    },
    getters: {
        getAchievements: (state) => {
            return state.achievements;
        },
        getAchievementsByUser: (state) => {
            return state.achievements;
        },
    },
    actions: {
        getAchievementByUser: ({commit}, userId) => {
            axios.get('/achievements/' + userId).then(function(response){
                commit('setAchievements', response.data);
            });
        },
        getAllAchievements: ({commit}) => {
            axios.get('/achievements').then(response => {
                commit('setAchievements', response.data.data);
            });
        },
    }
}