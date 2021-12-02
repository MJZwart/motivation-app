import axios from "axios";

export default {

    namespaced: true,

    state: {
        achievementsByUser: null,
        achievements: null,
        achievementTriggers: null,
    },
    mutations: {
        setAchievements: (state, achievements) => {
            state.achievements = achievements;
        },
        setAchievementsByUser: (state, achievements) => {
            state.achievements = achievements;
        },
        setAchievementTriggers: (state, triggers) => {
            state.achievementTriggers = triggers;
        },
    },
    getters: {
        getAchievements: (state) => {
            return state.achievements;
        },
        getAchievementsByUser: (state) => {
            return state.achievements;
        },
        getAchievementTriggers: (state) => {
            return state.achievementTriggers;
        },
    },
    actions: {
        getAllAchievements: ({commit}) => {
            axios.get('/achievements').then(response => {
                commit('setAchievements', response.data.data);
            });
        },
        getAchievementTriggers: ({commit}) => {
            axios.get('/achievements/triggers').then(response => {
                commit('setAchievementTriggers', response.data);
            });
        },
    }
}