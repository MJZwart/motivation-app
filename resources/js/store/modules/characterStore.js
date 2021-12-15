import axios from "axios";

export default {

    namespaced: true,

    state: {
        character: null,
        characters: null,
    },
    mutations: {
        setCharacter: (state, character) => {
            state.character = character;
        },
        setCharacters: (state, characters) => {
            state.characters = characters;
        },
    },
    getters: {
        getCharacter: (state) => {
            return state.character;
        },
        getCharacters: (state) => {
            return state.characters;
        },
    },
    actions: {
        //TODO will be addressed in user story #163
        fetchCharacter: ({commit}) => {
            axios.get('/character/active').then(response => {
                commit('setCharacter', response.data.data);
            });
        },
        fetchAllCharacters: ({commit}) => {
            return axios.get('/character/all').then(response => {
                commit('setCharacters', response.data.data);
                return Promise.resolve();
            });
        },
        fetchCharacterIfExists: ({commit}) => { // TODO Unnecesary?
            return axios.get('/character/find').then(response => {
                commit('setCharacter', response.data.data);
                return Promise.resolve();
            });
        },

        //TODO up until here
        updateCharacter: ({ commit, dispatch }, character) => {
            return axios.put('/character/' + character.id, character).then(function(response) {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('setCharacter', response.data.data);
                return Promise.resolve();
            });
        },
    }
}


/*
TODO
PLANS

Allow for multiple characters

API calls/controllers: 
- Fetch all characters
    Show these options to switch between when changing settings
    When choosing one of the characters, set it to active
- Fetch active character
    Only fetch active character if rewards type is set to 'character', either in front-end of back-end




*/