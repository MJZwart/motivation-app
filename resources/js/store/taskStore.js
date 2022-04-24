// @ts-nocheck
import {defineStore} from 'pinia';
import axios from 'axios';
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
            this.exampleTasks = data.data;
        },
        async storeTask(task) {
            const {data} = await axios.post('/tasks', task);
            this.taskLists = data.data;
        },
        async updateTask(task) {
            const {data} = await axios.put('/tasks/' + task.id, task);
            this.taskLists = data.data;
        },
        async deleteTask(task) {
            const {data} = await axios.delete('/tasks/' + task.id, task);
            this.taskLists = data.data;
        },
        async completeTask(task) {
            const {data} = await axios.put('/tasks/complete/' + task.id);
            this.taskLists = data.data;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.activeReward;
        },
        async storeTaskList(taskList) {
            const {data} = await axios.post('/tasklists', taskList);
            this.taskLists = data.data;
        },
        async updateTaskList(taskList) {
            const {data} = await axios.put('/tasklists/' + taskList.id, taskList);
            this.taskLists = data.data;
        },
        async deleteTaskList(taskList) {
            const {data} = await axios.delete('/tasklists/' + taskList.id);
            this.taskLists = data.data;
        },
        mergeTasks(data) {
            axios.post('/tasks/merge/' + data.taskListId, data);
        },
    },
});