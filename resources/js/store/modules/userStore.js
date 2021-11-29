import axios from "axios";
import router from '../../router/router.js';

export default {

    namespaced: true,
    
    state: {
        user: JSON.parse(localStorage.getItem('user')) || {},
        authenticated: JSON.parse(localStorage.getItem('authenticated')) || false,
        userProfile: {},
        userStats: null,
        searchResults: null,
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
        authenticated(state) {
            return state.authenticated;
        },
        getUser: (state) => {
            return state.user;
        },
        getUserProfile: (state) => {
            return state.userProfile;
        },
        getUserStats: (state) => {
            return state.userStats;
        },
        getSearchResults: (state) => {
            return state.searchResults;
        },
    },
    actions: {
        //User authentication
        login: ({ commit }, user) => {
            axios.get('http://localhost:8000/sanctum/csrf-cookie').then(csrfResponse => {
                axios.post('/login', user).then(response => {
                    commit('setUser', response.data.user);
                    commit('setAuthenticated', true);
                    router.push('/').catch(()=>{});
                });
            });
        },
        logout({ commit }) {
            axios.post('/logout').then(response => {
                commit('setUser', {});
                commit('setAuthenticated', false);
                router.push('/').catch(() => {
                    router.go();
                });
            });
        },

        //New user
        register: ({ commit }, user) => {
            axios.post('/register', user).then(response => {
                router.push('/login').catch(() => { });
                commit('setResponseMessage', response.data.message, {root:true});
            });
        },
        confirmRegister: ({ commit }, user) => {
            axios.post('/register/confirm', user).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setUser', response.data.user);
                router.push('/').catch(() => {});
            });
        },

        //Public user profile
        getUserProfile: ({ commit }, userId) => {
            axios.get('/profile/' + userId).then(response => {
                commit('setUserProfile', response.data.data);
            });
        },

        getOverview: ({ commit }) => {
            axios.get('/overview').then(response => {
                commit('setUserStats', response.data.stats);
                commit('achievement/setAchievements', response.data.achievements, {root:true});
                commit('character/setCharacter', response.data.character, {root:true});
            });
        },

        //TODO Redundant?
        // getUserStats: ({ commit }) => {
        //     axios.get('/user/stats').then(function(response){
        //         commit('setUserStats', response.data.data);
        //     });
        // },
        updatePassword: ({commit, dispatch}, passwords) => {
            axios.put('/user/settings/password', passwords).then(response => {
                dispatch('logout');
                commit('setResponseMessage', response.data.message, {root:true});
            });
        },
        updateEmail: ({commit}, email) => {
            axios.put('/user/settings/email', email).then(response => {
                commit('setUser', response.data.user);
                commit('setResponseMessage', response.data.message, {root:true});
            });
        },
        updateSettings: ({commit}, settings) => {
            axios.put('/user/settings', settings).then(response => {
                commit('setUser', response.data.user);
                commit('setResponseMessage', response.data.message, {root:true});
            });
        },

        searchUser: ({commit}, searchValue) => {
            axios.post('/search', searchValue).then(response => {
                commit('setSearchResults', response.data.data);
            });
        },
    },
}