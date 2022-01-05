// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        rewardObj: null,
        villages: null,
        character: null,
        characters: null,
    },
    mutations: {
        setRewardObj: (state, rewardObj) => {
            state.rewardObj = rewardObj;
        },
        setVillages: (state, villages) => {
            state.villages = villages;
        },
        setCharacter: (state, character) => {
            state.character = character;
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
        getCharacter: state => {
            return state.character;
        },
        getCharacters: state => {
            return state.characters;
        },
    },
    actions: {

        //TODO Unused so far
        fetchVillage: ({commit}) => {
            axios.get('/village/active').then(response => {
                commit('setVillage', response.data.data);
            });
        },


        fetchAllVillages: ({commit}) => {
            return axios.get('/village/all').then(response => {
                commit('setVillages', response.data.data);
                return Promise.resolve();
            });
        },

        //TODO unused so far
        fetchVillageIfExists: ({commit}) => {
            return axios.get('/village/find').then(response => {
                commit('setVillage', response.data.data);
                return Promise.resolve();
            });
        },

        //TODO unused so far
        updateVillage: ({commit, dispatch}, village) => {
            return axios.put('/village/' + village.id, village).then(function(response) {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('setVillage', response.data.data);
                return Promise.resolve();
            });
        },
        //TODO will be addressed in user story #163
        fetchCharacter: ({commit}) => {
            axios.get('/character/active').then(response => {
                commit('setCharacter', response.data.data);
            });
        },
        fetchAllCharacters: ({commit}) => {
            return axios.get('/character/all').then(response => {
                commit('setCharacters', response.data.data);
                return Promise.resolve();
            });
        },
        fetchCharacterIfExists: ({commit}) => { // TODO Unnecesary?
            return axios.get('/character/find').then(response => {
                commit('setCharacter', response.data.data);
                return Promise.resolve();
            });
        },

        //TODO up until here
        updateRewardObjName: ({commit, dispatch}, rewardObj) => {
            return axios.put('/reward/update', rewardObj).then(function(response) {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('setRewardObj', response.data.rewardObj);
                return Promise.resolve();
            });
        },
    },
}
