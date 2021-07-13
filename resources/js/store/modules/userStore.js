import axios from "axios";

export default {

    namespaced: true,
    
    state: {
        user: JSON.parse(localStorage.getItem('user')) || {},
        authenticated: JSON.parse(localStorage.getItem('authenticated')) || false,
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
    },
    getters: {
        authenticated(state) {
            return state.authenticated;
        },
        getUser: (state) => {
            return state.user;
        },
    },
    action: {
        
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

        register: ({ commit }, user) => {
            axios.post('/register', user).then(function (response) {
                router.push('/login').catch(() => { });
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success')
            });
        },

    },
}