import axios from "axios";
import Vue from "vue";
import Vuex from "vuex";
import router from '../router/router.js';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
    },
    getters: {
        getUser: (state) => {
            return state.user;
        }
    },
    actions: {

        login: ({ commit }, user) => {
            axios.post('/login', user).then(function (response) {
                console.log(response);
                commit('setUser', response.data);
                router.push('/');
            }).catch(function (error) {
                console.log(error);
            });
        },

        register: (_, user) => {
            console.log(user);
            axios.post('/register', user).then(function (response) {
                console.log(response);
                router.push('/login');
            }).catch(function (error) {
                console.log(error);
            });
        },

    }
});
