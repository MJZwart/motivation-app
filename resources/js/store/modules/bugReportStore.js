// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        bugReports: {},
    },
    mutations: {
        setBugReports(state, bugReports) {
            state.bugReports = bugReports;
        },
    },
    getters: {
        getBugReports: state => {
            return state.bugReports;
        },
    },
    actions: {
        fetchBugReports: ({commit}) => {
            axios.get('/bugreports').then(response => {
                commit('setBugReports', response.data.data);
            });
        },
        storeBugReport: ({dispatch}, bugReport) => {
            axios.post('/bugreport', bugReport).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },
    },
}