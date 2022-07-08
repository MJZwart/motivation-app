import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';

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
            try {
                const {data} = await axios.post('/friend/request/' + requestId + '/accept');
                this.friends = data.friends;
                this.requests = data.requests;
            } catch {
                this.handleError();
            }
        },
        /**
         * @param {Number} requestId
         */
        async denyRequest(requestId) {
            try {
                const {data} = await axios.post('/friend/request/' + requestId + '/deny');
                this.requests = data.requests;
            } catch {
                this.handleError();
            }
        },
        /**
         * @param {Number} requestId
         */
        async removeRequest(requestId) {
            try {
                const {data} = await axios.delete('/friend/request/' + requestId);
                this.requests = data.requests;
            } catch {
                this.handleError();
            }
        },
        /**
         * @param {Number} friendId
         */
        async removeFriend(friendId) {
            try {
                const {data} = await axios.delete('/friend/remove/' + friendId);
                this.friends = data.friends;
            } catch {
                this.handleError();
            }
        },

        /**
         * I've added this error handler with the try-catch blocks because either user can
         * make a change to the friendship. If one user deletes the friendship while the other
         * user is about to make a change to it, the back-end will throw a 404 and not send
         * a proper error response. Reloading the page should always fix this problem, although
         * this leaves 500's and other issues unchecked.
         */
        handleError() {
            const mainStore = useMainStore();
            mainStore.addToast({'Error': 'Something went wrong, please reload the page.'});
        },
    },
});