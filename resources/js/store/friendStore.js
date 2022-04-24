// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useUserStore} from './userStore';

export const useFriendStore = defineStore('friend', {
    state: () => {
        return {
            requests: null,
        }
    },
    actions: {
        async sendRequest(friendId) {
            await axios.post('/friend/request/' + friendId);
        },
        async getRequests() {
            const {data} = await axios.get('/friend/requests/all');
            this.requests = data;
        },
        async acceptRequest(requestId) {
            const {data} = await axios.post('/friend/request/' + requestId + '/accept');
            const userStore = useUserStore();
            userStore.user = data.user;
            this.requests = data.requests;
        },
        async denyRequest(requestId) {
            const {data} = await axios.post('/friend/request/' + requestId + '/deny');
            this.requests = data.requests;
        },
        async removeRequest(requestId) {
            const {data} = await axios.delete('/friend/request/' + requestId);
            this.requests = data.requests;
        },
        async removeFriend(friendId) {
            const {data} = await axios.delete('/friend/remove/' + friendId);
            const userStore = useUserStore();
            userStore.user = data.user;
        },
    },
});