// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';
import {useUserStore} from './userStore';

export const useFriendStore = defineStore('friend', {
    state: () => {
        return {
            requests: null,
        }
    },
    actions: {
        async sendRequest(friendId) {
            const {data} = await axios.post('/friend/request/' + friendId);
            console.log(data);
            this.addToast(data.message);
        },
        async getRequests() {
            const {data} = await axios.get('/friend/requests/all');
            console.log(data);
            this.requests = data;
        },
        async acceptRequest(requestId) {
            const {data} = await axios.post('/friend/request/' + requestId + '/accept');
            console.log(data);
            this.addToast(data.message);
            const userStore = useUserStore();
            userStore.user = data.user;
            this.requests = data.requests;
        },
        async denyRequest(requestId) {
            const {data} = await axios.post('/friend/request/' + requestId + '/deny');
            console.log(data);
            this.addToast(data.message);
            this.requests = data.requests;
        },
        async removeRequest(requestId) {
            const {data} = await axios.delete('/friend/request/' + requestId);
            console.log(data);
            this.addToast(data.message);
            this.requests = data.requests;
        },
        async removeFriend(friendId) {
            const {data} = await axios.delete('/friend/remove/' + friendId);
            console.log(data);
            this.addToast(data.message);
            const userStore = useUserStore();
            userStore.user = data.user;
        },
        addToast(toast) {
            const mainStore = useMainStore();
            mainStore.addToast(toast);
        },
    },
});