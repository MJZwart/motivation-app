import axios from 'axios';
import {defineStore} from 'pinia';
import {NewBugReport} from 'resources/types/bug';
import {Error} from 'resources/types/error';
import {NewFeedback} from 'resources/types/feedback';
import {useRewardStore} from './rewardStore';
import {useTaskStore} from './taskStore';

export const useMainStore = defineStore('main', {
    state: () => {
        return {
            //Errors and response
            /** @type import('resources/types/error.js').Error */
            errors: {},
            /** @type Array<import('resources/types/toast.js').Toast> */
            toasts: [],
        };
    },
    actions: {
        setErrorMessages(errorMessages: Error) {
            this.errors = errorMessages;
        },
        clearErrors() {
            this.errors = {};
        },
        async getDashboard() {
            const {data} = await axios.get('/dashboard');
            const taskStore = useTaskStore();
            taskStore.taskLists = data.taskLists;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.rewardObj;
        },
        async sendFeedback(feedback: NewFeedback) {
            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/feedback', feedback);
        },
        async storeBugReport(bugReport: NewBugReport) {
            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/bugreport', bugReport);
        },
    },
});
