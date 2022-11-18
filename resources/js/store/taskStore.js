import {defineStore} from 'pinia';
import axios from 'axios';
import {useRewardStore} from './rewardStore';

export const useTaskStore = defineStore('task', {
    state: () => {
        return {
            /** @type Array<import('resources/types/task').TaskList> */
            taskLists: [],
        };
    },

    actions: {
        /** @returns Array<import('resources/types/task').Task> */
        async fetchExampleTasks() {
            const {data} = await axios.get('/examples/tasks');
            return data.data;
        },
        /**
         * @param {import('resources/types/task').NewTask} task
         */
        async storeTask(task) {
            const {data} = await axios.post('/tasks', task);
            this.taskLists = data.data.taskLists;
        },
        /**
         * @param {import('resources/types/task').Task} task
         */
        async updateTask(task) {
            const {data} = await axios.put('/tasks/' + task.id, task);
            this.taskLists = data.data.taskLists;
        },
        /**
         * @param {import('resources/types/task').Task} task
         */
        async deleteTask(task) {
            const {data} = await axios.delete('/tasks/' + task.id);
            this.taskLists = data.data.taskLists;
        },
        /**
         * @param {import('resources/types/task').Task} task
         */
        async completeTask(task) {
            const {data} = await axios.put('/tasks/complete/' + task.id);
            this.taskLists = data.data.taskLists;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.data.activeReward;
        },
        /**
         * @param {import('resources/types/task').NewTaskList} taskList
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
         * @param {{taskListId: Number | string, tasks: Array<import('resources/types/task').Task>}} data
         */
        mergeTasks(data) {
            axios.post('/tasks/merge/' + data.taskListId, data);
        },
        /** @returns Array<import('resources/types/task').Templates> */
        async getTemplates() {
            const {data} = await axios.get('/tasks/templates');
            return data.data;
        },
        /** 
         * @param {import('resources/types/task').NewTemplate} newTemplate
         * @returns Array<import('resources/types/task').Templates>
         */
        async storeTemplate(newTemplate) {
            const {data} = await axios.post('/tasks/templates', newTemplate);
            return data.data;
        },        
        /** 
        * @param {import('resources/types/task').Template} template
        * @returns Array<import('resources/types/task').Templates>
        */
        async updateTemplate(template) {
            const {data} = await axios.put(`/tasks/templates/${template.id}`, template);
            return data.data;
        },
        /**
         * 
         * @param {number} templateId 
         * @returns Array<import('resources/types/task').Templates>
         */
        async deleteTemplate(templateId) {
            const {data} = await axios.delete(`/tasks/templates/${templateId}`);
            return data.data;
        },
    },
});
