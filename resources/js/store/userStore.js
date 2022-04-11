// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';

export const useRewardStore = defineStore('reward', {
    state: () => {
        return {
            user: JSON.parse(localStorage.getItem('user')) || {},
            authenticated: JSON.parse(localStorage.getItem('authenticated')) || false,
            userProfile: {},
            userStats: null,
            searchResults: null,
        }
    },
    mutations: {
        setAuthenticated(state, value) {
            state.authenticated = value;
            localStorage.setItem('authenticated', value);
        },
        setUser(state, value) {
            state.user = value;
            localStorage.setItem('user', JSON.stringify(value));
        },
        setUserProfile(state, value) {
            state.userProfile = value;
        },
        setUserStats(state, value) {
            state.userStats = value;
        },
        setSearchResults(state, value) {
            state.searchResults = value;
        },
    },
    getters: {
        authenticated: state => {
            return state.authenticated;
        },
        getUser: state => {
            return state.user;
        },
        getUserProfile: state => {
            return state.userProfile;
        },
        getUserStats: state => {
            return state.userStats;
        },
        getSearchResults: state => {
            return state.searchResults;
        },
    },
    actions: {
        //User authentication
        login: ({commit}, user) => {
            //axios.get('http://localhost:8000/sanctum/csrf-cookie').then(csrfResponse => {
            axios.get('http://localhost:8000/sanctum/csrf-cookie').then(_ => {
                axios.post('/login', user).then(response => {
                    commit('setUser', response.data.user);
                    commit('setAuthenticated', true);
                    router.push('/').catch(() => {});
                });
            });
        },
        logout({commit}) {
            axios.post('/logout').then(() => {
                commit('setUser', {});
                commit('setAuthenticated', false);
                router.push('/').catch(() => {
                    router.go();
                });
            });
        },

        //New user
        register: ({commit}, user) => {
            axios.post('/register', user).then(response => {
                router.push('/login').catch(() => { });
                commit('addToast', response.data.message, {root:true});
            });
        },
        confirmRegister: ({commit}, user) => {
            axios.post('/register/confirm', user).then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('setUser', response.data.user);
                router.push('/').catch(() => {});
            });
        },

        //Public user profile
        getUserProfile: ({commit}, userId) => {
            return axios.get('/profile/' + userId).then(response => {
                commit('setUserProfile', response.data.data);
                return Promise.resolve();
            });
        },

        getOverview: ({commit}) => {
            return axios.get('/overview').then(response => {
                commit('setUserStats', response.data.stats);
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                commit('reward/setRewardObj', response.data.rewardObj, {root:true});
                return Promise.resolve();
            });
        },

        updatePassword: ({commit, dispatch}, passwords) => {
            axios.put('/user/settings/password', passwords).then(response => {
                dispatch('logout');
                commit('addToast', response.data.message, {root:true});
            });
        },
        updateEmail: ({commit}, email) => {
            axios.put('/user/settings/email', email).then(response => {
                commit('setUser', response.data.user);
                commit('addToast', response.data.message, {root:true});
            });
        },
        updateSettings: ({commit}, settings) => {
            axios.put('/user/settings', settings).then(response => {
                commit('setUser', response.data.user);
                commit('addToast', response.data.message, {root:true});
            });
        },
        changeRewardType: ({commit}, user) => {
            return axios.put('/user/settings/rewards', user).then(response => {
                commit('setUser', response.data.user);
                commit('addToast', response.data.message, {root:true});
                commit('reward/setRewardObj', response.data.activeReward, {root:true});
                return Promise.resolve();
            });
        },

        searchUser: ({commit}, searchValue) => {
            axios.post('/search', searchValue).then(response => {
                commit('setSearchResults', response.data.data);
            });
        },

        reportUser: ({commit}, [user, report]) => {
            return axios.post('/user/' + user.id + '/report', report).then(response => {
                commit('addToast', response.data.message, {root:true});
                return Promise.resolve();
            });
        },

        blockUser: ({commit}, userId) => {
            return axios.put('/user/' + userId + '/block').then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('message/setConversations', response.data.data, {root:true});
                return Promise.resolve();
            });
        },
    },
});