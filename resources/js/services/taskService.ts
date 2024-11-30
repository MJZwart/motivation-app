import axios from 'axios';
import type {NewTask, NewTaskList, Task, Template} from 'resources/types/task';
import type { TaskList } from "resources/types/task";
import {ref} from 'vue';
import { activeVillage } from './villageService';

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

// Tasks

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
    if (task.super_task_id) return removeSubTask(task.id, task.super_task_id);
    const idx = tasks.value.findIndex(existingTask => existingTask.id === task.id);
    if (idx < 0) return;
    tasks.value.splice(idx, 1);
}

export const removeSubTask = (subTaskId: number, superTaskId: number) => {
    const superTaskIdx = tasks.value.findIndex(task => task.id === superTaskId);
    const subTaskIdx = tasks.value[superTaskIdx].tasks?.findIndex(subTask => subTask.id === subTaskId);
    if(subTaskIdx === undefined) return;
    tasks.value[superTaskIdx].tasks?.splice(subTaskIdx, 1);
}

export const updateTaskListForTasks = (fromTaskListId: number, toTaskListId: number) => {
    const filtered = tasks.value.filter(task => task.task_list_id === fromTaskListId);
    filtered.forEach(task => task.task_list_id = toTaskListId);
}

// Task Lists

export const addTaskList = (taskList: TaskList) => {
    taskLists.value.push(taskList);
}

export const updateTaskList = (updatedTaskList: TaskList) => {
    const idx = taskLists.value.findIndex(existingTaskList => existingTaskList.id === updatedTaskList.id);
    if (idx < 0) return;
    taskLists.value[idx] = updatedTaskList;
}

export const removeTaskListFromTaskLists = (taskList: TaskList) => {
    const idx = taskLists.value.findIndex(existingTaskList => existingTaskList.id === taskList.id);
    if (idx < 0) return;
    taskLists.value.splice(idx, 1);
}

// * API

export const fetchDashboard = async() => {
    const {data} = await axios.get('/user/dashboard');
    tasks.value = [];
    taskLists.value = data.taskLists;
    data.tasks.forEach((task: Task) => {
        addTaskToTasks(task);
    });
    activeVillage.value = data.village;
}

export const getTemplates = async() =>
{
    const {data} = await axios.get('/tasks/templates');
    templates.value = data.data;
}