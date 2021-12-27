// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        village: null,
        villages: null,
    },
    mutations: {
        setVillage: (state, village) => {
            state.village = village;
        },
        setVillages: (state, villages) => {
            state.villages = villages;
        },
    },
    getters: {
        getVillage: state => {
            return state.village;
        },
        getVillages: state => {
            return state.villages;
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
    },
}
