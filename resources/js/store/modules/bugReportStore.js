// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
    },
    mutations: {
    },
    getters: {
    },
    actions: {
        storeBugReport: ({dispatch}, bugReport) => {
            axios.post('/bugreport', bugReport).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
            });
        },
    },
}