import axios from "axios";
import router from '../../router/router.js';

export default {

    namespaced: true,
    
    state: {
        user: JSON.parse(localStorage.getItem('user')) || {},
        authenticated: JSON.parse(localStorage.getItem('authenticated')) || false,
        userProfile: {},
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
    },
    actions: {
        //User authentication
        login: ({ commit }, user) => {
            axios.get('http://localhost:8000/sanctum/csrf-cookie').then(response => {
                axios.post('/login', user).then(function (response) {
                    commit('setUser', response.data);
                    commit('setAuthenticated', true);
                    router.push('/').catch(()=>{});
                });
            });
        },
        logout({ commit }) {
            axios.post('/logout').then(function (response) {
                commit('setUser', {});
                commit('setAuthenticated', false);
                router.push('/').catch(() => {
                    router.go();
                });
            });
        },

        //New user
        register: ({ commit }, user) => {
            axios.post('/register', user).then(function (response) {
                router.push('/login').catch(() => { });
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
            });
        },

        //Public user profile
        getUserProfile: ({ commit }, userId) => {
            axios.get('/profile/' + userId).then(function(response){
                commit('setUserProfile', response.data.data);
            });
        },

    },
}