import axios from 'axios';
import {defineStore} from 'pinia';
import {errorToast} from '/js/services/toastService';

export const useFriendStore = defineStore('friend', {
    state: () => {
        return {
            /** @type import('resources/types/friend').FriendRequests */
            requests: {
                incoming: [],
                outgoing: [],
            },
            /** @type Array<import('resources/types/friend').Friend> | [] */
            friends: [],
        };
    },
    actions: {
        async sendRequest(friendId: number) {
            await axios.post('/friend/request/' + friendId);
        },
        async getRequests() {
            const {data} = await axios.get('/friend/requests/all');
            this.requests = data;
        },
        async getFriends() {
            const {data} = await axios.get('/friend');
            this.friends = data.friends;
        },
        async acceptRequest(requestId: number) {
            try {
                const {data} = await axios.post('/friend/request/' + requestId + '/accept');
                this.friends = data.data.friends;
                this.requests = data.data.requests;
                return true;
            } catch (e) {
                return this.handleError(e);
            }
        },
        async denyRequest(requestId: number) {
            try {
                const {data} = await axios.post('/friend/request/' + requestId + '/deny');
                this.requests = data.data.requests;
                return true;
            } catch (e) {
                return this.handleError(e);
            }
        },
        async removeRequest(requestId: number) {
            try {
                const {data} = await axios.delete('/friend/request/' + requestId);
                this.requests = data.data.requests;
                return true;
            } catch (e) {
                return this.handleError(e);
            }
        },
        async removeFriend(friendId: number) {
            try {
                const {data} = await axios.delete('/friend/remove/' + friendId);
                this.friends = data.data.friends;
                return true;
            } catch (e) {
                return this.handleError(e);
            }
        },

        /**
         * I've added this error handler with the try-catch blocks because either user can
         * make a change to the friendship. If one user deletes the friendship while the other
         * user is about to make a change to it, the back-end will throw a 404 and not send
         * a proper error response. Reloading the page should always fix this problem, although
         * this leaves 500's and other issues unchecked.
         */
        handleError(e: unknown) {
            //@ts-ignore
            if (e.response.data.data.error === 'FRIEND_DELETED') {
                return true;
            } else {
                errorToast('Something went wrong, please reload the page.');
                return false;
            }
        },
    },
});
