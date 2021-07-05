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

        //TaskLists
        taskLists: {},

        //Character
        experienceTable: null,
        character: {},
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

        //TaskLists
        setTaskLists(state, taskLists) {
            state.taskLists = taskLists;
        },

        //Character
        setExperienceTable: (state, experience) => {
            state.experienceTable = experience;
        },
        setCharacter: (state, character) => {
            state.character = character;
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

        //TaskLists
        getTaskLists: (state) => {
            return state.taskLists;
        },

        //Character
        getExperienceTable: (state) => {
            return state.experienceTable;
        },
        getCharacter: (state) => {
            return state.character;
        },
    },
    actions: {
        clearInformationBlock({ commit }) {
                commit('setResponseMessage', []);
                commit('setStatus', 'hidden')
        },
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

        //TaskLists
        getTaskLists: ({ commit }) => {
            axios.get('/tasklists').then(function (response) {
                commit('setTaskLists', response.data.data);
            });
        },
        storeTaskList: ({ commit }, taskList) => {
            return axios.post('/tasklists', taskList).then(function (response) {
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('setTaskLists', response.data.data);
                return Promise.resolve();
            });
        },
        updateTaskList: ({ commit }, taskList) => {
            return axios.put('/tasklists/' + taskList.id, taskList).then(function (response) {
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('setTaskLists', response.data.data);
                return Promise.resolve();
            });
        },
        deleteTaskList: ({ commit }, taskList) => {
            axios.delete('/tasklists/' + taskList.id).then(function(response){
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('setTaskLists', response.data.data);
            });
        },
        mergeTasks: ({}, data) => {
            axios.post('/tasks/merge/' + data.taskListId, data).then(function(){
            });
        },

        //Tasks
        storeTask: ({ commit }, task) => {
            return axios.post('/tasks', task).then(function (response) {
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('setTaskLists', response.data.data);
                return Promise.resolve();
            });
        },
        updateTask: ({ commit }, task) => {
            return axios.put('/tasks/' + task.id, task).then(function (response) {
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('setTaskLists', response.data.data);
                return Promise.resolve();
            });
        },
        deleteTask: ({ commit }, task) =>{
            axios.delete('/tasks/' + task.id, task).then(function(response) {
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('setTaskLists', response.data.data);
            });
        },
        completeTask: ({commit}, task) => {
            axios.put('/tasks/complete/' + task.id).then(function(response){
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('setTaskLists', response.data.data);
                commit('setCharacter', response.data.character);
            });
        },

        //Character
        getExperienceTable: ({commit}) => {
            axios.get('/experience').then(function(response){
                commit('setExperienceTable', response.data);
            });
        },

        getCharacter: ({commit}) => {
            axios.get('/character').then(function(response){
                commit('setCharacter', response.data.data);
            });
        },
    }
});
