import Vue from "vue";
import Vuex from "vuex";

import taskListStore from './modules/taskListStore.js';
import taskStore from './modules/taskStore.js';
import userStore from './modules/userStore.js';
import characterStore from './modules/characterStore.js';
import notificationStore from './modules/notificationStore.js';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        taskList: taskListStore,
        task: taskStore,
        user: userStore,
        character: characterStore,
        notification: notificationStore,
    },

    state: {
        //Errors and response
        responseMessage: {},
        status: "",
    },
    mutations: {
        //Errors and response
        setResponseMessage(state, responseMessage){
            state.responseMessage = responseMessage;
        },
        setStatus(state, status) {
            state.status = status;
        },
    },
    getters: {
        //Errors and response
        getResponseMessage: (state) => {
            return state.responseMessage;
        },
        getStatus: (state) => {
            return state.status;
        },
    },
    actions: {
        clearInformationBlock({ commit }) {
                commit('setResponseMessage', []);
                commit('setStatus', 'hidden')
        },
    }
});
