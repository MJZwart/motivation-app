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
        storeTask: ({commit, dispatch}, task) => {
            return axios.post('/tasks', task).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
                return Promise.resolve();
            });
        },
        updateTask: ({commit, dispatch}, task) => {
            return axios.put('/tasks/' + task.id, task).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
                return Promise.resolve();
            });
        },
        deleteTask: ({commit, dispatch}, task) => {
            axios.delete('/tasks/' + task.id, task).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
            });
        },
        completeTask: ({commit, dispatch}, task) => {
            axios.put('/tasks/complete/' + task.id).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('taskList/setTaskLists', response.data.data, {root:true});
                commit('reward/setRewardObj', response.data.character, {root:true});
            });
        },
        fetchExampleTasks: ({commit}) => {
            axios.get('/examples/tasks').then(response => {
                commit('setExampleTasks', response.data.data);
            });
        },
    },
}