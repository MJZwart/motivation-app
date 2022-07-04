import axios from 'axios';
import {defineStore} from 'pinia';

export const useFriendStore = defineStore('friend', {
    state: () => {
        return {
            /** @type Array<import('resources/types/friend').Friend> | null */
            requests: null,
            /** @type Array<import('resources/types/friend').Friend> | null */
            friends: [],
        }
    },
    actions: {
        /**
         * @param {Number} friendId
         */
        async sendRequest(friendId) {
            await axios.post('/friend/request/' + friendId);
        },
        async getRequests() {
            const {data} = await axios.get('/friend/requests/all');
            this.requests = data;
        },
        /**
         * @param {Number} requestId
         */
        async acceptRequest(requestId) {
            const {data} = await axios.post('/friend/request/' + requestId + '/accept');
            this.friends = data.friends;
            this.requests = data.requests;
        },
        /**
         * @param {Number} requestId
         */
        async denyRequest(requestId) {
            const {data} = await axios.post('/friend/request/' + requestId + '/deny');
            this.requests = data.requests;
        },
        /**
         * @param {Number} requestId
         */
        async removeRequest(requestId) {
            const {data} = await axios.delete('/friend/request/' + requestId);
            this.requests = data.requests;
        },
        /**
         * @param {Number} friendId
         */
        async removeFriend(friendId) {
            const {data} = await axios.delete('/friend/remove/' + friendId);
            this.friends = data.friends;
        },
    },
});