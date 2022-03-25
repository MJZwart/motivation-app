// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        rewardObj: null,
        villages: null,
        characters: null,
    },
    mutations: {
        setRewardObj: (state, rewardObj) => {
            state.rewardObj = rewardObj;
        },
        setVillages: (state, villages) => {
            state.villages = villages;
        },
        setCharacters: (state, characters) => {
            state.characters = characters;
        },
    },
    getters: {
        getRewardObj: state => {
            return state.rewardObj;
        },
        getVillages: state => {
            return state.villages;
        },
        getCharacters: state => {
            return state.characters;
        },
    },
    actions: {

        fetchAllVillages: ({commit}) => {
            return axios.get('/village/all').then(response => {
                commit('setVillages', response.data.data);
                return Promise.resolve();
            });
        },

        fetchAllCharacters: ({commit}) => {
            return axios.get('/character/all').then(response => {
                commit('setCharacters', response.data.data);
                return Promise.resolve();
            });
        },

        fetchAllRewardInstances: ({commit}) => {
            return axios.get('/rewards/all').then(response => {
                commit('setCharacters', response.data.rewards.characters);
                commit('setVillages', response.data.rewards.villages);
            })
        },

        updateRewardObjName: ({dispatch}, rewardObj) => {
            return axios.put('/reward/update', rewardObj).then(function(response) {
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            });
        },

        activateInstance: ({commit, dispatch}, rewardObj) => {
            return axios.put('/reward/activate', rewardObj).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('user/setUser', response.data.user, {root:true});
                return Promise.resolve();
            });
        },

        deleteInstance: ({dispatch}, rewardObj) => {
            return axios.put('/reward/delete', rewardObj).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            });
        },
    },
}
