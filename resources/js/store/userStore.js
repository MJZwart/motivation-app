// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';
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
            this.setUser(data.user, true);
            router.push('/dashboard').catch(() => {});
        },
        async logout() {
            await axios.post('/logout');
            this.setUser({}, false);
            router.push('/').catch(() => {
                router.go();
            });
        },
        setUser(user, auth) {
            this.user = user;
            this.authenticated = auth;
            localStorage.setItem('authenticated', auth);
            localStorage.setItem('user', JSON.stringify(user));
        },

        //New user
        async register(user) {
            const {data} = await axios.post('/register', user);
            console.log(data);
            router.push('/login').catch(() => { });
            this.addToast(data.message);
        },
        async confirmRegister(user) {
            const {data} = await axios.post('/register/confirm', user);
            console.log(data);
            this.addToast(data.message);
            this.user = data.user;
            router.push('/').catch(() => {});
        },

        //Public user profile
        async getUserProfile(userId) {
            const {data} = await  axios.get('/profile/' + userId);
            console.log(data);
            this.userProfile = data.data;
        },

        async getOverview() {
            const {data} = await  axios.get('/overview');
            console.log(data);
            this.userStats = data.stats;
            const achievementStore = useAchievementStore();
            achievementStore.achievementsByUser = data.achievements;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.rewardObj;
        },

        async updatePassword(passwords) {
            const {data} = await axios.put('/user/settings/password', passwords);
            console.log(data);
            this.logout();
            this.addToast(data.message);
        },
        async updateEmail(email) {
            const {data} = await axios.put('/user/settings/email', email);
            console.log(data);
            this.user = data.user;
            this.addToast(data.message);
        },
        async updateSettings(settings) {
            const {data} = await axios.put('/user/settings', settings);
            console.log(data);
            this.user = data.user;
            this.addToast(data.message);
        },
        async changeRewardType(user) {
            const {data} = await  axios.put('/user/settings/rewards', user);
            console.log(data);
            this.user  = data.user;
            this.addToast(data.message);
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.activeReward;
        },

        async searchUser(searchValue) {
            const {data} = await axios.post('/search', searchValue);
            console.log(data);
            this.searchResults = data.data;
        },

        async reportUser([user, report]) {
            const {data} = await  axios.post('/user/' + user.id + '/report', report);
            console.log(data);
            this.addToast(data.message);
        },

        async blockUser(userId) {
            const {data} = await axios.put('/user/' + userId + '/block');
            console.log(data);
            this.addToast(data.message);
            const messageStore = useMessageStore();
            messageStore.conversations = data.data;
        },
        addToast(toast) {
            const mainStore = useMainStore();
            mainStore.addToast(toast);
        },
    },
});