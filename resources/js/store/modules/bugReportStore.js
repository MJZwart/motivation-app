// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        bugs: {},
    },
    mutations: {
        setBugs(state, bugs) {
            state.bugs = bugs;
        },
    },
    getters: {
        getBugs: state => {
            return state.bugs;
        },
    },
    actions: {
        fetchBugs: ({commit}) => {
            axios.get('/bugreports').then(response => {
                commit('setBugs', response.data.data);
            });
        },
        storeBugReport: ({dispatch}, bugReport) => {
            axios.post('/bugreport', bugReport).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },
    },
}