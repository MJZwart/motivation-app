import {defineStore} from 'pinia';
import axios from 'axios';
import {useRewardStore} from './rewardStore';

export const useTaskStore = defineStore('task', {
    state: () => {
        return {
            /** @type Array<import('resources/types/task').Task> */
            exampleTasks: [],
            /** @type Array<import('resources/types/task').Task> */
            taskLists: [],
        }
    },
    
    actions: {
        async fetchExampleTasks() {
            const {data} = await axios.get('/examples/tasks');
            this.exampleTasks = data.data;
        },
        /**
         * @param {import('resources/types/task').Task} task
         */
        async storeTask(task) {
            const {data} = await axios.post('/tasks', task);
            this.taskLists = data.data;
        },
        /**
         * @param {import('resources/types/task').Task} task
         */
        async updateTask(task) {
            const {data} = await axios.put('/tasks/' + task.id, task);
            this.taskLists = data.data;
        },
        /**
         * @param {import('resources/types/task').Task} task
         */
        async deleteTask(task) {
            const {data} = await axios.delete('/tasks/' + task.id);
            this.taskLists = data.data;
        },
        /**
         * @param {import('resources/types/task').Task} task
         */
        async completeTask(task) {
            const {data} = await axios.put('/tasks/complete/' + task.id);
            this.taskLists = data.data;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.activeReward;
        },
        /**
         * @param {import('resources/types/task').TaskList} taskList
         */
        async storeTaskList(taskList) {
            const {data} = await axios.post('/tasklists', taskList);
            this.taskLists = data.data;
        },
        /**
         * @param {import('resources/types/task').TaskList} taskList
         */
        async updateTaskList(taskList) {
            const {data} = await axios.put('/tasklists/' + taskList.id, taskList);
            this.taskLists = data.data;
        },
        /**
         * @param {import('resources/types/task').TaskList} taskList
         */
        async deleteTaskList(taskList) {
            const {data} = await axios.delete('/tasklists/' + taskList.id);
            this.taskLists = data.data;
        },
        /**
         * @param {{taskListId: Number, tasks: Array<import('resources/types/task').Task>}} data
         */
        mergeTasks(data) {
            axios.post('/tasks/merge/' + data.taskListId, data);
        },
    },
});