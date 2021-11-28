import Vue from "vue";
import Vuex from "vuex";

import taskListStore from './modules/taskListStore.js';
import taskStore from './modules/taskStore.js';
import userStore from './modules/userStore.js';
import characterStore from './modules/characterStore.js';
import friendStore from './modules/friendStore.js';
import notificationStore from './modules/notificationStore.js';
import achievementStore from './modules/achievementStore.js';
import adminStore from './modules/adminStore.js';
import toastService from '../services/toastService';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        taskList: taskListStore,
        task: taskStore,
        user: userStore,
        character: characterStore,
        friend: friendStore,
        notification: notificationStore,
        achievement: achievementStore,
        admin: adminStore,
    },
    state: {
        //Errors and response
        responseMessage: {},
        errors: [],
        // status: "",
    },
    mutations: {
        //Errors and response
        setErrorMessages(state, response){
            state.errors = response;
        },
        setResponseMessage(state, responseMessage){
            toastService.$emit('message', {message: responseMessage.message[0], variant: "success"});
        },
        setStatus(state, status) {
            //state.status = status;
        },
    },
    getters: {
        //Errors and response
        getResponseMessage: (state) => {
            return state.responseMessage;
        },
        getErrorMessages: (state) => {
            return state.errors;
        },
        getStatus: (state) => {
            //return state.status;
        },
    },
    actions: {
        clearErrors({ commit }) {
            commit('setErrorMessages', []);
                // commit('setStatus', 'hidden')
        },
    }
});
