// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        exampleTasks: null,
    },
    mutations: {
        setExampleTasks: (state, exampleTasks) => {
            state.exampleTasks = exampleTasks;
        },
    },
    getters: {
        getExampleTasks: state => {
            return state.exampleTasks;
        },
    },
    actions: {
        storeTask: ({commit}, task) => {
            return axios.post('/tasks', task).then(response => {
                // commit('addToast', response.data.message, {root:true});
                commit('addToast', response.data.message, {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
                return Promise.resolve();
            });
        },
        updateTask: ({commit}, task) => {
            return axios.put('/tasks/' + task.id, task).then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
                return Promise.resolve();
            });
        },
        deleteTask: ({commit}, task) => {
            axios.delete('/tasks/' + task.id, task).then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
            });
        },
        completeTask: ({commit}, task) => {
            axios.put('/tasks/complete/' + task.id).then(response => {
                commit('addToast', response.data.message, {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
                commit('reward/setRewardObj', response.data.activeReward, {root:true});
            });
        },
        fetchExampleTasks: ({commit}) => {
            axios.get('/examples/tasks').then(response => {
                commit('setExampleTasks', response.data.data);
            });
        },
    },
}