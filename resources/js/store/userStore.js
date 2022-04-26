// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import router from '../router/router';
import {useRewardStore} from './rewardStore';
import {useMessageStore} from './messageStore';
import {useAchievementStore} from './achievementStore';

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            user: JSON.parse(localStorage.getItem('user')) || {},
            authenticated: JSON.parse(localStorage.getItem('authenticated')) || false,
            userProfile: {},
            userStats: null,
            searchResults: null,
        }
    },
    getters: {
        isAdmin(state) {
            return state.user.admin;
        },
    },
    actions: {
        //User authentication
        async login(user) {
            //axios.get('http://localhost:8000/sanctum/csrf-cookie').then(csrfResponse => {
            await axios.get('http://localhost:8000/sanctum/csrf-cookie');
            const {data} = await axios.post('/login', user);
            this.setUser(data.user);
            router.push('/dashboard').catch(() => {});
        },
        async logout() {
            await axios.post('/logout');
            this.setUser({}, false);
            router.push('/').catch(() => {
                router.go();
            });
        },
        setUser(user, auth = true) {
            this.user = user;
            this.authenticated = auth;
            localStorage.setItem('authenticated', auth);
            localStorage.setItem('user', JSON.stringify(user));
        },

        //New user
        async register(user) {
            await axios.post('/register', user);
            router.push('/login').catch(() => { });
        },
        async confirmRegister(user) {
            const {data} = await axios.post('/register/confirm', user);
            this.setUser(data.user);
            router.push('/').catch(() => {});
        },

        //Public user profile
        async getUserProfile(userId) {
            const {data} = await  axios.get('/profile/' + userId);
            this.userProfile = data.data;
        },

        async getOverview() {
            const {data} = await  axios.get('/overview');
            this.userStats = data.stats;
            const achievementStore = useAchievementStore();
            achievementStore.achievementsByUser = data.achievements;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.rewardObj;
        },

        async updatePassword(passwords) {
            await axios.put('/user/settings/password', passwords);
            this.logout();
        },
        async updateEmail(email) {
            const {data} = await axios.put('/user/settings/email', email);
            this.setUser(data.user);
        },
        async updateSettings(settings) {
            const {data} = await axios.put('/user/settings', settings);
            this.setUser(data.user);
        },
        async changeRewardType(user) {
            const {data} = await  axios.put('/user/settings/rewards', user);
            this.setUser(data.user);
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.activeReward;
        },

        async searchUser(searchValue) {
            const {data} = await axios.post('/search', searchValue);
            this.searchResults = data.data;
        },

        async reportUser([user, report]) {
            await  axios.post('/user/' + user.id + '/report', report);
        },

        async blockUser(userId) {
            const {data} = await axios.put('/user/' + userId + '/block');
            const messageStore = useMessageStore();
            messageStore.conversations = data.data;
        },
    },
});