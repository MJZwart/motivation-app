// @ts-nocheck
import {defineStore} from 'pinia';
import axios from 'axios';
import {useMainStore} from './store';
import {useRewardStore} from './rewardStore';

export const useTaskStore = defineStore('task', {
    state: () => {
        return {
            exampleTasks: [],
            taskLists: {},
        }
    },
    
    actions: {
        async fetchExampleTasks() {
            const {data} = await axios.get('/examples/tasks');
            console.log(data);
            this.exampleTasks = data.data;
        },
        async storeTask(task) {
            const {data} = await axios.post('/tasks', task);
            this.taskLists = data.data;
            this.addToast(data.message);
        },
        async updateTask(task) {
            const {data} = await axios.put('/tasks/' + task.id, task);
            console.log(data);
            this.taskLists = data.data;
            this.addToast(data.message);
        },
        async deleteTask(task) {
            const {data} = await axios.delete('/tasks/' + task.id, task);
            console.log(data);
            this.taskLists = data.data;
            this.addToast(data.message);
        },
        async completeTask(task) {
            const {data} = await axios.put('/tasks/complete/' + task.id);
            console.log(data);
            this.taskLists = data.data;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.activeReward;
            this.addToast(data.message);
        },
        async storeTaskList(taskList) {
            const {data} = await axios.post('/tasklists', taskList);
            console.log(data);
            this.addToast(data.message);
            this.taskLists = data.data;
        },
        async updateTaskList(taskList) {
            const {data} = await axios.put('/tasklists/' + taskList.id, taskList);
            console.log(data);
            this.addToast(data.message);
            this.taskLists = data.data;
        },
        async deleteTaskList(taskList) {
            const {data} = await axios.delete('/tasklists/' + taskList.id);
            console.log(data);
            this.addToast(data.message);
            this.taskLists = data.data;
        },
        mergeTasks(data) {
            axios.post('/tasks/merge/' + data.taskListId, data);
        },
        addToast(toast) {
            const mainStore = useMainStore();
            mainStore.addToast(toast);
        },
    },
});