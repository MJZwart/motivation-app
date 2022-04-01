// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        experiencePoints: null,
        charExpGain: null,
        villageExpGain: null,
        reportedUsers: null,
        conversation: null,
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
        setReportedUsers(state, reportedUsers) {
            state.reportedUsers = reportedUsers;
        },
        setConversation(state, conversation) {
            state.conversation = conversation;
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
            return state.villageExpGain;
        },
        getBalancing: state => {
            return {
                'experience_points': state.experiencePoints,
                'character_exp_gain': state.charExpGain,
                'village_exp_gain': state.villageExpGain,
            }
        },
        getReportedUsers: state => {
            return state.reportedUsers;
        },
        getConversation: state => {
            return state.conversation;
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
                commit('setReportedUsers', response.data.reportedUsers);
                return Promise.resolve();
            });
        },
        sendNotification: ({commit}, notification) => {
            axios.post('/notifications/all', notification).then(response => {
                commit('addToast', response.data.message, {root:true});
            });
        },
        fetchConversation: ({commit}, id) => {
            return axios.get(`/admin/conversation/${id}`).then(response => {
                commit('setConversation', response.data.data);
                return Promise.resolve();
            });
        },

        //Balancing
        updateExpPoints: ({commit}, experiencePoints) => {
            axios.put('/admin/experience_points', experiencePoints).then(response => {
                commit('setExperiencePoints', response.data.data);
                commit('addToast', response.data.message, {root:true});
            });
        },
        addNewLevel: ({commit}, newLevel) => {
            return axios.post('/admin/experience_points', newLevel).then(response => {
                commit('setExperiencePoints', response.data.data);
                commit('addToast', response.data.message, {root:true});
                return Promise.resolve();
            });
        },
        updateCharExpGain: ({commit}, charExpGain) => {
            axios.put('/admin/character_exp_gain', charExpGain).then(response => {
                commit('setCharExpGain', response.data.data);
                commit('addToast', response.data.message, {root:true});
            });
        },
        updateVillageExpGain: ({commit}, villageExpGain) => {
            axios.put('/admin/village_exp_gain', villageExpGain).then(response => {
                commit('setVillageExpGain', response.data.data);
                commit('addToast', response.data.message, {root:true});
            });
        },
    },
}