// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        experiencePoints: null,
        charExpGain: null,
        villageExpGain: null,
    },
    mutations: {
        setExperiencePoints(state, experiencePoints) {
            state.experiencePoints = experiencePoints;
        },
        setCharExpGain(state, charExpGain) {
            state.charExpGain = charExpGain;
        },
        setVillageExpGain(state, villageExpGain) {
            state.villageExpGain = villageExpGain;
        },
    },
    getters: {
        isAdmin: (state, getters, rootState, rootGetters) => {
            return rootGetters['user/getUser'].admin;
        },
        getExperiencePoints: state => {
            return state.experiencePoints;
        },
        getCharExpGain: state => {
            return state.charExpGain;
        },
        getVillageExpGain: state => {
            return state.charExpGain;
        },
        getBalancing: state => {
            return {
                'experience_points': state.experiencePoints,
                'character_exp_gain': state.charExpGain,
                'village_exp_gain': state.villageExpGain,
            }
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
                commit('setExperiencePoints', response.data.balancing.experience_points);
                commit('setCharExpGain', response.data.balancing.character_exp_gain);
                commit('setVillageExpGain', response.data.balancing.village_exp_gain);
                return Promise.resolve();
            });
        },
        sendNotification: ({dispatch}, notification) => {
            axios.post('/notifications/all', notification).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },

        //Balancing
        updateExpPoints: ({dispatch, commit}, experiencePoints) => {
            axios.put('/admin/experience_points', experiencePoints).then(response => {
                commit('setExperiencePoints', response.data.data);
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },
    },
}