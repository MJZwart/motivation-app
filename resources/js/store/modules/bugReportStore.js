import axios from "axios";

export default {

    namespaced: true,
    state: {
    },
    mutations: {
    },
    getters: {
    },
    actions: {
        storeBugReport: ({ commit }, bugReport) => {
            return axios.post('/bugReport', bugReport).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                /*commit('taskList/setTaskLists', response.data.data, {root:true});*/
                return Promise.resolve();
            });
        },
        /*updateTask: ({ commit }, task) => {
            return axios.put('/tasks/' + task.id, task).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
                return Promise.resolve();
            });
        },
        deleteTask: ({ commit }, task) =>{
            axios.delete('/tasks/' + task.id, task).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
            });
        },
        completeTask: ({commit}, task) => {
            axios.put('/tasks/complete/' + task.id).then(response => {
                commit('setResponseMessage', response.data.message, {root:true});
                commit('setStatus', 'success', {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
                commit('character/setCharacter', response.data.character, {root:true});
            });
        },
        fetchExampleTasks: ({commit}) => {
            axios.get('/examples/tasks').then(response => {
                commit('setExampleTasks', response.data.data);
            });
        },*/
    },
}