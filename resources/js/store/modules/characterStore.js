import axios from "axios";

export default {

    namespaced: true,

    state: {
        character: null,
    },
    mutations: {
        setCharacter: (state, character) => {
            state.character = character;
        },
    },
    getters: {
        getCharacter: (state) => {
            return state.character;
        },
    },
    actions: {
        getCharacter: ({commit}) => {
            axios.get('/character').then(response => {
                commit('setCharacter', response.data.data);
            });
        },
        updateCharacter: ({commit}, character) => {
            return axios.put('/character/' + character.id, character).then(function(response) {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setCharacter', response.data.data);
                return Promise.resolve();
            });
        },
    }
}