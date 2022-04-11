// @ts-nocheck
import {defineStore} from 'pinia';
import axios from 'axios';

export const useTasklistStore = defineStore('tasklist', {
    state: () => {
        return {
            taskLists: {},
        }
    },
    actions: {
        async storeTaskList(taskList) {
            const data = await axios.post('/tasklists', taskList)
            // commit('addToast', response.data.message, {root:true});
            this.taskLists = data.data;
        },
        async updateTaskList(taskList) {
            const data = await axios.put('/tasklists/' + taskList.id, taskList)
            // commit('addToast', response.data.message, {root:true});
            this.taskLists = data.data;
        },
        async deleteTaskList(taskList) {
            const data = await axios.delete('/tasklists/' + taskList.id)
            // commit('addToast', response.data.message, {root:true});
            this.taskLists = data.data;
        },
        mergeTasks(data) {
            axios.post('/tasks/merge/' + data.taskListId, data);
        },
    },
});
