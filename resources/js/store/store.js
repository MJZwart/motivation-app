import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {},
    mutations: {},
    getters: {},
    actions: {

        login: ({ commit }, user) => {
            axios.post('/login', user).then(function (response) {
                console.log('success');
                console.log(response);
            }).catch(function (error) {
                console.log(error);
            });
        },

    }
});
