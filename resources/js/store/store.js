import Vue from 'vue';
import Vuex from 'vuex';

import taskListStore from './modules/taskListStore.js';
import taskStore from './modules/taskStore.js';
import userStore from './modules/userStore.js';
import friendStore from './modules/friendStore.js';
import notificationStore from './modules/notificationStore.js';
import achievementStore from './modules/achievementStore.js';
import adminStore from './modules/adminStore.js';
import bugReportStore from './modules/bugReportStore.js';
import rewardStore from './modules/rewardStore.js';
import toastService from '../services/toastService';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        taskList: taskListStore,
        task: taskStore,
        user: userStore,
        friend: friendStore,
        notification: notificationStore,
        achievement: achievementStore,
        admin: adminStore,
        bugReport: bugReportStore,
        reward: rewardStore,
    },
    state: {
        //Errors and response
        responseMessage: {},
        errors: [],
    },
    mutations: {
        //Errors and response
        setErrorMessages(state, response) {
            state.errors = response;
        },
    },
    getters: {
        //Errors and response
        getErrorMessages: state => {
            return state.errors;
        },
    },
    actions: {
        clearErrors({commit}) {
            commit('setErrorMessages', []);
        },
        getDashboard: ({commit}) => {
            axios.get('/dashboard').then(response => {
                commit('taskList/setTaskLists', response.data.taskLists, {root:true});
                commit('reward/setRewardObj', response.data.rewardObj, {root:true});
            });
        },
        /**
         * Send a toast by calling:
         * dispatch('sendToasts', response.data.message, {root:true});
         * where 'response.data.message' is an object with one or multiple messages.
         * In the JsonResponse, name the response message 'success', 'danger' or 'info' 
         * to get corresponding themes and titles.
         * 
         * @param {Object} messages 
         */
        sendToasts(_, messages) {
            Object.entries(messages).forEach(msg => {
                const [key, value] = msg;
                toastService.$emit('message', {message: value, key: key})
            });
        },
    },
});
