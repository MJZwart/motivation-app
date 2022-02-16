// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        bugReports: null,
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
            axios.get('/bugreport/all').then(response => {
                commit('setBugReports', response.data.data);
            });
        },
        updateBugReport: ({commit, dispatch}, bugReport) => {
            return axios.put('/bugreport/' + bugReport.id, bugReport).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('setBugReports', response.data.data);
                return Promise.resolve();
            });
        },
        storeBugReport: ({dispatch}, bugReport) => {
            axios.post('/bugreport', bugReport).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },
    },
}