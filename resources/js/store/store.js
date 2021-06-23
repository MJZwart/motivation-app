import axios from "axios";
import Vue from "vue";
import Vuex from "vuex";
import router from '../router/router.js';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: JSON.parse(localStorage.getItem('user')) || {},
        authenticated: JSON.parse(localStorage.getItem('authenticated')) || false,

        //Errors and response
        responseMessage: {},
        status: "",
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

        //Errors and response
        setResponseMessage(state, responseMessage){
            state.responseMessage = responseMessage;
        },
        setStatus(state, status) {
            state.status = status;
        },
    },
    getters: {
        authenticated(state) {
            return state.authenticated;
        },
        getUser: (state) => {
            return state.user;
        },

        //Errors and response
        getResponseMessage: (state) => {
            return state.responseMessage;
        },
        getStatus: (state) => {
            return state.status;
        },
    },
    actions: {
        //User authentication
        login: ({ commit }, user) => {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/login', user).then(function (response) {
                    commit('setUser', response.data);
                    commit('setAuthenticated', true);
                    router.push('/').catch(()=>{});
                });
            });
        },
        logout({ commit }) {
            commit('setUser', {});
            commit('setAuthenticated', false);
            router.push('/').catch(() => {
                router.go();
            });
        },

        register: ({ commit }, user) => {
            axios.post('/register', user).then(function (response) {
                router.push('/login').catch(() => { });
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success')
            });
        },

    }
});
