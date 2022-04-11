import {defineStore} from 'pinia';
import axios from 'axios';

export const useTaskStore = defineStore('task', {
    state: () => {
        return {
            exampleTasks: 'test',
        }
    },
    
    actions: {
        async fetchExampleTasks() {
            const data = await axios.get('/examples/tasks')
            this.exampleTasks = data.data;
        },
        // storeTask: (task) => {
        //     return axios.post('/tasks', task).then(response => {
        //         commit('addToast', response.data.message, {root:true});
        //         commit('taskList/setTaskLists', response.data.data, {root:true});
        //         return Promise.resolve();
        //     });
        // },
        // updateTask: (task) => {
        //     return axios.put('/tasks/' + task.id, task).then(response => {
        //         commit('addToast', response.data.message, {root:true});
        //         commit('taskList/setTaskLists', response.data.data, {root:true});
        //         return Promise.resolve();
        //     });
        // },
        // deleteTask: (task) => {
        //     axios.delete('/tasks/' + task.id, task).then(response => {
        //         commit('addToast', response.data.message, {root:true});
        //         commit('taskList/setTaskLists', response.data.data, {root:true});
        //     });
        // },
        // completeTask: (task) => {
        //     axios.put('/tasks/complete/' + task.id).then(response => {
        //         commit('addToast', response.data.message, {root:true});
        //         commit('taskList/setTaskLists', response.data.data, {root:true});
        //         commit('reward/setRewardObj', response.data.activeReward, {root:true});
        //     });
        // },
    },
})