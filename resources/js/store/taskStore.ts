import {defineStore} from 'pinia';
import axios from 'axios';
import {useRewardStore} from './rewardStore';
import {NewTask, NewTaskList, Task, TaskList, Template} from 'resources/types/task';

export const useTaskStore = defineStore('task', {
    state: () => {
        return {
            taskLists: [] as TaskList[],
        };
    },

    actions: {
        async fetchExampleTasks(): Promise<Task[]>
        {
            const {data} = await axios.get('/examples/tasks');
            return data.data;
        },
        async storeTask(task: NewTask) {
            const {data} = await axios.post('/tasks', task);
            this.taskLists = data.data.taskLists;
        },
        async updateTask(task: Task) {
            const {data} = await axios.put('/tasks/' + task.id, task);
            this.taskLists = data.data.taskLists;
        },
        async deleteTask(taskId: number) {
            const {data} = await axios.delete('/tasks/' + taskId);
            this.taskLists = data.data.taskLists;
        },
        async completeTask(taskId: number) {
            const {data} = await axios.put('/tasks/complete/' + taskId);
            this.taskLists = data.data.taskLists;
            const rewardStore = useRewardStore();
            rewardStore.rewardObj = data.data.activeReward;
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
        async getTemplates(): Promise<Template[]>
        {
            const {data} = await axios.get('/tasks/templates');
            return data.data;
        },
        async storeTemplate(newTemplate: Template) : Promise<Template[]>
        {
            const {data} = await axios.post('/tasks/templates', newTemplate);
            return data.data;
        },
    },
});
