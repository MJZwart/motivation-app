import axios from 'axios';
import type {NewTask, NewTaskList, Task, Template} from 'resources/types/task';
import type { TaskList } from "resources/types/task";
import {ref} from 'vue';
import { activeReward } from './villageService';

export const taskLists = ref<TaskList[]>([]);
export const tasks = ref<Task[]>([]);
export const templates = ref<Template[]>([]);

export function getNewTask(taskListId: number): NewTask {
    return {
        name: '',
        description: '',
        difficulty: 3,
        type: 'GENERIC',
        repeatable: 'NONE',
        task_list_id: taskListId,
        repeatable_reset_days: [],
    }
}
export function getNewTaskList(): NewTaskList {
    return {
        name: '',
    }
}


export const addTaskToTasks = (newTask: Task) => {
    if (!newTask.super_task_id) {
        tasks.value.push(newTask);
        return;
    }
    const superTaskIdx = tasks.value.findIndex(task => task.id === newTask.super_task_id);
    if (tasks.value[superTaskIdx].tasks === undefined) tasks.value[superTaskIdx].tasks = [];
    tasks.value[superTaskIdx].tasks?.push(newTask);
}

export const updateTaskInTasks = (task: Task) => {
    const idx = tasks.value.findIndex(existingTask => existingTask.id === task.id);
    tasks.value[idx] = task;
}

export const removeTaskFromTasks = (task: Task) => {
    const idx = tasks.value.findIndex(existingTask => existingTask.id === task.id);
    if (idx < 0) return;
    tasks.value.splice(idx, 1);
}

// * API

export const fetchDashboard = async() => {
    const {data} = await axios.get('/user/dashboard');
    tasks.value = [];
    taskLists.value = data.taskLists;
    data.tasks.forEach((task: Task) => {
        addTaskToTasks(task);
    });
    activeReward.value = data.rewardObj; // TODO Make sure the naming is correct
}