import axios from "axios";

export default {

    namespaced: true,
    
    state: {
        //TaskLists
        taskLists: {},
    },
    mutations: {
        //TaskLists
        setTaskLists(state, taskLists) {
            state.taskLists = taskLists;
        },
    },
    getters: {
        //TaskLists
        getTaskLists: (state) => {
            return state.taskLists;
        },
    },
    actions: {
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
    }
};
