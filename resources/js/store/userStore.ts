import axios from 'axios';
import {defineStore} from 'pinia';
import router from '../router/router';
import {useRewardStore} from './rewardStore';
import {useMessageStore} from './messageStore';
import {useAchievementStore} from './achievementStore';
import {useFriendStore} from './friendStore';
import {PasswordSettings, ProfileSettings} from 'resources/types/settings';
import {ChangeReward} from 'resources/types/reward';
import {Login, NewUser, Register, User} from 'resources/types/user';
import {UserSearch} from 'resources/types/global';
import {NewReportedUser} from 'resources/types/admin';
import {Message} from 'resources/types/message';

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            /** @type import('resources/types/user').User | any = {} */
            user: JSON.parse(localStorage.getItem('user') || '{}') || {},
            /** @type Boolean */
            authenticated: JSON.parse(localStorage.getItem('authenticated') || '{}') || false,
            /** @type import('resources/types/user').UserStats | null */
            userStats: null,
        };
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
         */
        async login(user: Login): Promise<Message | null> {
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
        setUser(user: User | Record<string, never>, auth = true) {
            this.user = user;
            this.authenticated = auth;
            localStorage.setItem('authenticated', auth.toString());
            localStorage.setItem('user', JSON.stringify(user));
        },

        //New user
        async register(user: Register) {
            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/register', user);
            router.push('/login');
        },
        async confirmRegister(user: NewUser) {
            const {data} = await axios.post('/register/confirm', user);
            this.setUser(data.user);
            // eslint-disable-next-line @typescript-eslint/no-empty-function
            router.push('/').catch(() => {});
        },

        //Public user profile
        async getUserProfile(userId: number) {
            const {data} = await axios.get('/profile/' + userId);
            return data.data;
        },

        async getOverview() {
            const {data} = await axios.get('/overview');
            this.userStats = data.stats;
            const achievementStore = useAchievementStore();
            achievementStore.achievementsByUser = data.achievements;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.rewardObj;
        },

        async updatePassword(passwords: PasswordSettings) {
            await axios.put('/user/settings/password', passwords);
            this.logout();
        },
        async updateEmail(email: string) {
            const {data} = await axios.put('/user/settings/email', email);
            this.setUser(data.user);
        },
        async updateSettings(settings: ProfileSettings) {
            const {data} = await axios.put('/user/settings', settings);
            this.setUser(data.user);
        },
        async changeRewardType(change: ChangeReward) {
            const {data} = await axios.put('/user/settings/rewards', change);
            this.setUser(data.user);
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.activeReward;
        },

        async searchUser(searchValue: UserSearch): Promise<User[]> {
            const {data} = await axios.post('/search', searchValue);
            return data.data;
        },

        async reportUser(userId: number, report: NewReportedUser) {
            await axios.post('/user/' + userId + '/report', report);
        },

        async blockUser(userId: string | number) {
            const {data} = await axios.put('/user/' + userId + '/block');
            const messageStore = useMessageStore();
            messageStore.conversations = data.data;
        },

        async getBlocklist(): Promise<User> {
            const {data} = await axios.get('/user/blocklist');
            return data.blockedUsers;
        },

        async unblockUser(blocklistId: number) {
            const {data} = await axios.put(`/user/${blocklistId}/unblock`);
            return data.blockedUsers;
        },
    },
});
