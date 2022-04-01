// @ts-nocheck
import axios from 'axios';

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
        getAchievements: state => {
            return state.achievements;
        },
        getAchievementsByUser: state => {
            return state.achievements;
        },
        getAchievementTriggers: state => {
            return state.achievementTriggers;
        },
    },
    actions: {
        newAchievement: ({commit}, achievement) => {
            return axios.post('/achievements', achievement).then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                return Promise.resolve();
            });
        },
        editAchievement: ({commit}, achievement) => {
            return axios.put('/achievements/' + achievement.id, achievement).then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                return Promise.resolve();
            });
        },
    },
}