import axios from "axios";

export default {

    namespaced: true,
    
    state: {
        taskLists: {},
    },
    mutations: {
        setTaskLists(state, taskLists) {
            state.taskLists = taskLists;
        },
    },
    getters: {
        getTaskLists: (state) => {
            return state.taskLists;
        },
    },
    actions: {
        getTaskLists: ({ commit }) => {
            axios.get('/tasklists').then(response => {
                commit('setTaskLists', response.data.data);
            });
        },
        storeTaskList: ({ commit }, taskList) => {
            return axios.post('/tasklists', taskList).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                commit('setTaskLists', response.data.data);
                return Promise.resolve();
            });
        },
        updateTaskList: ({ commit }, taskList) => {
            return axios.put('/tasklists/' + taskList.id, taskList).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                commit('setTaskLists', response.data.data);
                return Promise.resolve();
            });
        },
        deleteTaskList: ({ commit }, taskList) => {
            axios.delete('/tasklists/' + taskList.id).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                commit('setTaskLists', response.data.data);
            });
        },
        mergeTasks: ({}, data) => {
            axios.post('/tasks/merge/' + data.taskListId, data);
        },
    }
};
