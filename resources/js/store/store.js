// @ts-nocheck
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
            state.toasts.push(toast);
        },
        clearToast(state, title) {
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
    },
});

import {defineStore} from 'pinia';

import {useRewardStore} from './rewardStore.js';
import {useTaskStore} from './taskStore.js';

export const useMainStore = defineStore('main', {
    state: () => {
        return {
            //Errors and response
            responseMessage: {},
            errors: [],
            toasts: [],
        };
    },
    actions: {
        /**
         * Send a toast by calling:
         * useMainStore.addToast(toastObject)
         * where 'response.data.message' is an object with one or multiple messages.
         * In the JsonResponse, name the message key 'success', 'danger' or 'info' 
         * to get corresponding themes and titles.
         */
        setErrorMessages(errorMessages) {
            this.errors = errorMessages;
        },
        addToast(toast) {
            this.toasts.push(toast);
        },
        clearToast(title) {
            const index = this.toasts.findIndex(toast => toast.title === title);
            this.toasts.splice(index, 1);
        },
        clearErrors() {
            this.errors = [];
        },
        async getDashboard() {
            const {data} = await axios.get('/dashboard');
            const taskStore = useTaskStore();
            taskStore.taskLists = data.taskLists;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.rewardObj;
        },
        async sendFeedback(feedback) {
            await axios.post('/feedback', feedback);
        },
        async storeBugReport(bugReport) {
            await axios.post('/bugreport', bugReport);
        },
    },
});