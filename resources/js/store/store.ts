import axios from 'axios';
import {defineStore} from 'pinia';
import {NewBugReport} from 'resources/types/bug';
import {NewFeedback} from 'resources/types/feedback';

export const useMainStore = defineStore('main', {
    state: () => {
        return {
            /** @type Array<import('resources/types/toast.js').Toast> */
            toasts: [],
        };
    },
    actions: { 
        async sendFeedback(feedback: NewFeedback) {
            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/feedback', feedback);
        },
        async storeBugReport(bugReport: NewBugReport) {
            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/bugreport', bugReport);
        },
        async getCurrentMaintenanceBanners() {
            const {data} = await axios.get('/maintenance-banner');
            return data;
        },
    },
});
