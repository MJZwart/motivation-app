import axios from "axios";

export default {

    namespaced: true,

    state: {
        achievements: null,
    },
    mutations: {
        setAchievements: (state, achievements) => {
            state.achievements = achievements;
        },
    },
    getters: {
        getAchievements: (state) => {
            return state.achievements;
        },
    },
    actions: {
        getAchievements: ({commit}, userId) => {
            axios.get('/achievements/' + userId).then(function(response){
                commit('setAchievements', response.data);
            });
        },
    }
}