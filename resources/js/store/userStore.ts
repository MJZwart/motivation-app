import axios from 'axios';
import {defineStore} from 'pinia';
import router from '../router/router';
import {useRewardStore} from './rewardStore';
import {useFriendStore} from './friendStore';
import type {EmailSettings, PasswordSettings, ProfileSettings} from 'resources/types/settings';
import {changeLang} from '../services/languageService';
import type {ChangeReward} from 'resources/types/reward';
import type {Blocked, Login, NewUser, Register, ResetPassword, User, UserStats} from 'resources/types/user';
import type {UserSearch} from 'resources/types/global';
import type {NewReportedUser} from 'resources/types/admin';
import {Achievement} from 'resources/types/achievement';
import {useTaskStore} from './taskStore';

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            user: null as User | null,
            authenticated: false,
            userStats: null as UserStats | null,
            isAdmin: false,
            achievementsByUser: null as Achievement[] | null,
        };
    },
    actions: {
        // * Authentication
        /**
         * User authentication. If user login is valid but the account is otherwise invalidated,
         * instead return info the Login screen.
         */
        async login(user: Login): Promise<string | null> {
            await axios.get('/sanctum/csrf-cookie');
            const {data} = await axios.post('/login', user);
            this.setUser(data.user);
            const friendStore = useFriendStore();
            friendStore.friends = data.user.friends;
            // eslint-disable-next-line @typescript-eslint/no-empty-function
            router.push('/dashboard').catch(() => {});
            return null;
        },

        async logout() {
            await axios.post('/logout');
            this.setUser(null, false);
            router.push('/').catch(() => {
                router.forward();
            });
        },
        async getMe() {
            const {data} = await axios.get('/me');
            this.setUser(data.user, !!data.user);
        },
        setUser(user: User | null, auth = true) {
            this.user = user;
            this.authenticated = auth;
            if (auth && user) this.setLanguage(user.language);
            this.isAdmin = user?.admin ?? false;
        },
        setLanguage(lang: string) {
            if (lang !== 'en' && lang !== 'nl') return;
            changeLang(lang);
        },

        async getDashboard() {
            const {data} = await axios.get('/user/dashboard');
            const taskStore = useTaskStore();
            taskStore.taskLists = data.taskLists;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.rewardObj;
        },

        // * New user
        async register(user: Register) {
            await axios.get('/sanctum/csrf-cookie');
            const {data} = await axios.post('/register', user);
            this.setUser(data.data.user);
            const friendStore = useFriendStore();
            friendStore.friends = data.data.user.friends;
            // eslint-disable-next-line @typescript-eslint/no-empty-function
            router.push('/dashboard').catch(() => {});
        },
        async confirmRegister(user: NewUser) {
            const {data} = await axios.post('/register/confirm', user);
            this.setUser(data.data.user);
            // eslint-disable-next-line @typescript-eslint/no-empty-function
            router.push('/').catch(() => {});
        },

        // * Public user profile
        async getUserProfile(userId: number) {
            const {data} = await axios.get('/profile/' + userId);
            return data.data;
        },

        // * Overview
        async getOverview() {
            const {data} = await axios.get('/user/overview');
            this.userStats = data.stats;
            this.achievementsByUser = data.achievements;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.rewardObj;
        },
        async getTimeline(userId: number) {
            const {data} = await axios.get(`/user/timeline/${userId}`);
            return data;
        },

        // * Settings
        async updatePassword(passwords: PasswordSettings) {
            await axios.put('/user/settings/password', passwords);
            this.logout();
        },
        async updateEmail(emailSettings: EmailSettings) {
            const {data} = await axios.put('/user/settings/email', emailSettings);
            this.setUser(data.data.user);
        },
        async toggleTutorial(tutorialSettings: {show: boolean}) {
            const {data} = await axios.put('/user/settings/tutorial', tutorialSettings);
            this.setUser(data.data.user);
        },
        async updateSettings(settings: ProfileSettings) {
            const {data} = await axios.put('/user/settings', settings);
            this.setUser(data.data.user);
        },

        async updateLanguage(language: {language: string}) {
            const {data} = await axios.put('/user/settings/language', language);
            this.setUser(data.data.user);
        },

        async changeRewardType(change: ChangeReward) {
            const {data} = await axios.put('/user/settings/rewards', change);
            this.setUser(data.data.user);
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.data.activeReward;
        },

        async searchUser(searchValue: UserSearch): Promise<User[]> {
            const {data} = await axios.post('/search', searchValue);
            return data.data;
        },

        async reportUser(userId: number, report: NewReportedUser) {
            await axios.post('/user/' + userId + '/report', report);
        },

        async blockUser(userId: string | number) {
            await axios.put('/user/' + userId + '/block');
        },

        async getBlocklist(): Promise<Blocked[]> {
            const {data} = await axios.get('/user/blocklist');
            return data.blockedUsers;
        },

        async unblockUser(blocklistId: number) {
            const {data} = await axios.put(`/user/${blocklistId}/unblock`);
            return data.data.blockedUsers;
        },

        async resetPassword(email: {email: string}) {
            return await axios.post('/send-reset-password', email);
        },
        async setNewPassword(newPassword: ResetPassword) {
            return await axios.post('/password/reset', newPassword);
        },
    },
});
