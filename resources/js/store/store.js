
import axios from 'axios';
import {defineStore} from 'pinia';
import {useRewardStore} from './rewardStore.js';
import {useTaskStore} from './taskStore.js';

export const useMainStore = defineStore('main', {
    state: () => {
        return {
            //Errors and response
            /** @type Array<import('resources/types/error.js').Error> */
            errors: [],
            /** @type Array<import('resources/types/toast.js').Toast> */
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
         * @param {[]} errorMessages
         */
        setErrorMessages(errorMessages) {
            this.errors = errorMessages;
        },
        /**
         * @param {import('resources/types/toast.js').Toast} toast
         */
        addToast(toast) {
            this.toasts.push(toast);
        },
        clearToast() {
            this.toasts.splice(0, 1);
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
        /**
         * @param {import('resources/types/bug.js').Feedback} feedback
         */
        async sendFeedback(feedback) {
            await axios.post('/feedback', feedback);
        },
        /**
         * @param {import('resources/types/bug.js').BugReport} bugReport
         */
        async storeBugReport(bugReport) {
            await axios.post('/bugreport', bugReport);
        },
    },
});