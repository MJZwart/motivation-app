import axios from 'axios';
import {defineStore} from 'pinia';
import router from '../router/router';
import {useRewardStore} from './rewardStore';
import {useMessageStore} from './messageStore';
import {useAchievementStore} from './achievementStore';
import {useFriendStore} from './friendStore';

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            /** @type import('resources/types/user').User | any = {} */
            user: JSON.parse(localStorage.getItem('user') || '{}') || {},
            /** @type Boolean */
            authenticated: JSON.parse(localStorage.getItem('authenticated') || '{}') || false,
            /** @type import('resources/types/user').UserStats | null */
            userStats: null,
        }
    },
    getters: {
        isAdmin(state) {
            return state.user ? state.user.admin : false;
        },
    },
    actions: {
        /**
         * User authentication. If user login is valid but the account is otherwise invalidated, 
         * instead return info the Login screen.
         * @param {import('resources/types/user').Login} user
         * @returns {Promise<import('resources/types/error').Message | null>}
         */
        async login(user) {
            await axios.get('/sanctum/csrf-cookie');
            const {data} = await axios.post('/login', user);
            if (data.invalid) return data.message;
            this.setUser(data.user);
            const friendStore = useFriendStore();
            friendStore.friends = data.user.friends;
            // eslint-disable-next-line @typescript-eslint/no-empty-function
            router.push('/dashboard').catch(() => {});
            return null;
        },

        async logout() {
            await axios.post('/logout');
            this.setUser({}, false);
            router.push('/').catch(() => {
                router.forward();
            });
        },
        /**
         * @param {import('resources/types/user').User | {}} user
         * @param {Boolean} auth
         */
        setUser(user, auth = true) {
            this.user = user;
            this.authenticated = auth;
            localStorage.setItem('authenticated', auth.toString());
            localStorage.setItem('user', JSON.stringify(user));
        },

        //New user
        /**
         * @param {import('resources/types/user').Register} user
         */
        async register(user) {
            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/register', user);
            router.push('/login');
        },
        /**
         * @param {import('resources/types/user').User} user
         */
        async confirmRegister(user) {
            const {data} = await axios.post('/register/confirm', user);
            this.setUser(data.user);
            // eslint-disable-next-line @typescript-eslint/no-empty-function
            router.push('/').catch(() => {});
        },

        //Public user profile
        /**
         * @param {Number} userId
         */
        async getUserProfile(userId) {
            const {data} = await  axios.get('/profile/' + userId);
            return data.data;
        },

        async getOverview() {
            const {data} = await  axios.get('/overview');
            this.userStats = data.stats;
            const achievementStore = useAchievementStore();
            achievementStore.achievementsByUser = data.achievements;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.rewardObj;
        },

        /**
         * @param {{String, String, String}} passwords
         */
        async updatePassword(passwords) {
            await axios.put('/user/settings/password', passwords);
            this.logout();
        },
        /**
         * @param {String} email
         */
        async updateEmail(email) {
            const {data} = await axios.put('/user/settings/email', email);
            this.setUser(data.user);
        },
        /**
         * @param {{Boolean, Boolean, Boolean}} settings
         */
        async updateSettings(settings) {
            const {data} = await axios.put('/user/settings', settings);
            this.setUser(data.user);
        },
        /**
         * @param {import('resources/types/user').User} user
         */
        async changeRewardType(user) {
            const {data} = await  axios.put('/user/settings/rewards', user);
            this.setUser(data.user);
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.activeReward;
        },

        /**
         * @param {import('resources/types/global').UserSearch} searchValue
         * @returns Array<import('resources/types/user').User>
         */
        async searchUser(searchValue) {
            const {data} = await axios.post('/search', searchValue);
            return data.data;
        },

        /**
         * @param {[import('resources/types/user').User, import('resources/types/admin').ReportedUser]} searchValue
         */
        async reportUser([user, report]) {
            await  axios.post('/user/' + user.id + '/report', report);
        },

        /**
         * @param {string | number} userId
         */
        async blockUser(userId) {
            const {data} = await axios.put('/user/' + userId + '/block');
            const messageStore = useMessageStore();
            messageStore.conversations = data.data;
        },
        /**
         * @returns Array<import('resources/types/user').User>
         */
        async getBlocklist() {
            const {data} = await axios.get('/user/blocklist');
            return data.blockedUsers;
        },

        /**
         * @param {Number} blocklistId 
         */
        async unblockUser(blocklistId) {
            const {data} = await axios.put(`/user/${blocklistId}/unblock`);
            return data.blockedUsers;
        },
    },
});