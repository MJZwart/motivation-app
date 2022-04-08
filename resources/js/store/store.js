// import Vue from 'vue';
import {createStore} from 'vuex';

import taskListStore from './modules/taskListStore.js';
import taskStore from './modules/taskStore.js';
import userStore from './modules/userStore.js';
import friendStore from './modules/friendStore.js';
import notificationStore from './modules/notificationStore.js';
import achievementStore from './modules/achievementStore.js';
import adminStore from './modules/adminStore.js';
import bugReportStore from './modules/bugReportStore.js';
import rewardStore from './modules/rewardStore.js';
import messageStore from './modules/messageStore.js';
import groupsStore from './modules/groupsStore.js';
import axios from 'axios';

// Vue.use(Vuex);

export const store = createStore({
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
        message: messageStore,
        groups: groupsStore,
    },
    state: {
        //Errors and response
        responseMessage: {},
        errors: [],
        toasts: [],
    },
    mutations: {
        //Errors and response
        setErrorMessages(state, response) {
            state.errors = response;
        },
        addToast(state, toast) {
            // @ts-ignore
            state.toasts.push(toast);
        },
        clearToast(state, title) {
            // @ts-ignore
            const index = state.toasts.findIndex(toast => toast.title === title);
            state.toasts.splice(index, 1);
        },
    },
    getters: {
        //Errors and response
        getErrorMessages: state => {
            return state.errors;
        },
        getToasts: state => {
            return state.toasts;
        },
    },
    actions: {
        clearErrors({commit}) {
            commit('setErrorMessages', []);
        },
        getDashboard: ({commit}) => {
            return axios.get('/dashboard').then(response => {
                commit('taskList/setTaskLists', response.data.taskLists, {root:true});
                commit('reward/setRewardObj', response.data.rewardObj, {root:true});
                return Promise.resolve();
            });
        },
        hasUnread: ({commit}) => {
            axios.get('/unread').then(function(response) {
                commit('notification/setHasNotifications', response.data.hasNotifications);
                commit('message/setHasMessages', response.data.hasMessages);
            });
        },
        sendFeedback: ({commit}, feedback) => {
            axios.post('/feedback', feedback).then(response => {
                commit('addToast', response.data.message);
            })
        },
        /**
         * Send a toast by calling:
         * commit('addToast', response.data.message, {root:true});
         * where 'response.data.message' is an object with one or multiple messages.
         * In the JsonResponse, name the response message 'success', 'danger' or 'info' 
         * to get corresponding themes and titles.
         * 
         */
        sendToasts: ({commit}, message) => {
            commit('addToast', message);
        },
    },
});
