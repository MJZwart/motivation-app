// @ts-nocheck
import axios from 'axios';
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