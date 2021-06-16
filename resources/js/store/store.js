import Vue from "vue";
import Vuex from "vuex";

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
                console.log('success');
                console.log(response);
                commit('setUser', response.data);
            }).catch(function (error) {
                console.log(error);
            });
        },

    }
});
