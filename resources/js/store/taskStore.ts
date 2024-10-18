import {defineStore} from 'pinia';
import axios from 'axios';
import {useRewardStore} from './rewardStore';
import type {NewTask, NewTaskList, NewTemplate, Task, TaskList, Template} from 'resources/types/task';
import {templates} from '/js/services/taskService';

export const useTaskStore = defineStore('task', {
    state: () => {
        return {
            taskLists: [] as TaskList[],
        };
    },

    actions: {
        // * Task lists
        async getOtherTaskLists(taskListId: number) {
            const {data} = await axios.get(`/tasklists/other/${taskListId}`);
            return data;
        },
        async storeTaskList(taskList: NewTaskList) {
            const {data} = await axios.post('/tasklists', taskList);
            this.taskLists = data.data;
        },
        async updateTaskList(taskList: TaskList) {
            const {data} = await axios.put('/tasklists/' + taskList.id, taskList);
            this.taskLists = data.data;
        },
        async deleteTaskList(taskListId: number) {
            const {data} = await axios.delete('/tasklists/' + taskListId);
            this.taskLists = data.data;
        },
        mergeTasks(data: {taskListId: number | string, tasks: Task[]}) {
            axios.post('/tasks/merge/' + data.taskListId, data);
        },
        // * Templates
        async getTemplates(): Promise<Template[]>
        {
            const {data} = await axios.get('/tasks/templates');
            templates.value = data.data;
            return data.data;
        },
        async storeTemplate(newTemplate: NewTemplate) : Promise<Template[]>
        {
            const {data} = await axios.post('/tasks/templates', newTemplate);
            templates.value = data.data;
            return data.data;
        },
        async updateTemplate(template: Template): Promise<Template[]>
        {
            const {data} = await axios.put(`/tasks/templates/${template.id}`, template);
            templates.value = data.data;
            return data.data;
        },
        async deleteTemplate(templateId: number): Promise<Template[]>
        {
            const {data} = await axios.delete(`/tasks/templates/${templateId}`);
            templates.value = data.data;
            return data.data;
        },
    },
});
