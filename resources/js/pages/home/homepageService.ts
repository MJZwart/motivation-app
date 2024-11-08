import {successToast} from '/js/services/toastService';
import {ref} from 'vue';
import {DUMMY_VILLAGE, DUMMY_TASK_LIST, taskId} from '/js/constants/dummyConstants';
import {NewTask, Task, TaskList} from 'resources/types/task';
import {waitingOnResponse} from '/js/services/loadingService';
import i18n from '/js/i18n';

export const dummyVillageRef = ref(Object.assign({}, DUMMY_VILLAGE));

export const dummyTaskListRef = ref(Object.assign({}, DUMMY_TASK_LIST));

export function completeTask(task: Task) {
    const taskIndex = dummyTaskListRef.value.tasks.findIndex(foundTask => foundTask.id === task.id);
    if (taskIndex < 0) return;
    calculateReward(dummyTaskListRef.value.tasks[taskIndex]);
    dummyTaskListRef.value.tasks.splice(taskIndex, 1);
    successToast(i18n.global.t('example-task-completed'));
}
export function completeSubTask(task: Task) {
    const superTaskIndex = dummyTaskListRef.value.tasks.findIndex(superTask => superTask.id === task.super_task_id);
    if (superTaskIndex < 0) return;
    const subTaskIndex = dummyTaskListRef.value.tasks[superTaskIndex].tasks?.findIndex(subTask => subTask.id === task.id);
    if (subTaskIndex !== undefined && subTaskIndex < 0) return;
    calculateReward(task);
    // @ts-ignore This is checked above
    dummyTaskListRef.value.tasks[superTaskIndex].tasks.splice(subTaskIndex, 1);
    successToast(i18n.global.t('example-task-completed'));
}

export function submitTask({task}: {task: NewTask}) {
    const taskWithId = {...task, id: taskId.value++, tasks: []};
    dummyTaskListRef.value.tasks.push(taskWithId);
    waitingOnResponse.value = false;
    successToast(i18n.global.t('example-task-created'));
}

export function submitSubTask({task}: {task: NewTask}) {
    const superTaskIdx = dummyTaskListRef.value.tasks.findIndex(superTask => superTask.id === task.super_task_id);
    const taskWithId = {...task, id: taskId.value++};
    dummyTaskListRef.value.tasks[superTaskIdx].tasks?.push(taskWithId);
    waitingOnResponse.value = false;
    successToast(i18n.global.t('example-task-created'));
}

export function submitEditTask({task}: {task: Task}) {
    if (task.super_task_id) {
        editSubTask(task);
    } else {
        const taskIndex = dummyTaskListRef.value.tasks.findIndex(foundTask => foundTask.id === task.id);
        if (taskIndex !== undefined && taskIndex < 0) return;
        dummyTaskListRef.value.tasks[taskIndex] = task;
    }
    waitingOnResponse.value = false;
    successToast(i18n.global.t('example-task-edited'));
}

export function deleteTask(task: Task) {
    if (task.super_task_id) {
        deleteSubTask(task);
    } else {
        const taskIndex = dummyTaskListRef.value.tasks.findIndex(foundTask => foundTask.id === task.id);
        if (taskIndex !== undefined && taskIndex < 0) return;
        dummyTaskListRef.value.tasks.splice(taskIndex, 1);
    }
    successToast(i18n.global.t('example-task-deleted'));
}

export function submitEditTaskList(taskList: TaskList) {
    dummyTaskListRef.value.name = taskList.name;
    waitingOnResponse.value = false;
    successToast(i18n.global.t('example-task-list-edited'));
}

function deleteSubTask(task: Task) {
    const superTaskIndex = dummyTaskListRef.value.tasks.findIndex(superTask => superTask.id === task.super_task_id);
    if (superTaskIndex < 0) return;
    const taskIndex = dummyTaskListRef.value.tasks[superTaskIndex].tasks?.findIndex(subTask => subTask.id === task.id);
    if (taskIndex !== undefined && taskIndex < 0) return;
    // @ts-ignore This is checked above
    dummyTaskListRef.value.tasks[superTaskIndex].tasks.splice(taskIndex, 1);
}

function editSubTask(task: Task) {
    const superTaskIndex = dummyTaskListRef.value.tasks.findIndex(superTask => superTask.id === task.super_task_id);
    if (superTaskIndex < 0) return;
    const taskIndex = dummyTaskListRef.value.tasks[superTaskIndex].tasks?.findIndex(subTask => subTask.id === task.id);
    if (taskIndex !== undefined && taskIndex < 0) return;
    // @ts-ignore This is checked above
    dummyTaskListRef.value.tasks[superTaskIndex].tasks[taskIndex] = task;
}

const stats: DummyVillageKeys[] = ['economy', 'labour', 'craft', 'art', 'community'];
const statExp: DummyVillageKeys[] = ['economy_exp', 'labour_exp', 'craft_exp', 'art_exp', 'community_exp'];
const statExpNeeded: DummyVillageKeys[] =
    ['economy_exp_needed', 'labour_exp_needed', 'craft_exp_needed', 'art_exp_needed', 'community_exp_needed'];

function calculateReward(task: Task) {
    if (!task) return;
    for (let i = 0 ; i < stats.length ; i++) {
        dummyVillageRef.value[statExp[i]] += getRandomIntBetween(25, 75) * task?.difficulty;
        if (dummyVillageRef.value[statExp[i]] > dummyVillageRef.value[statExpNeeded[i]]) {
            dummyVillageRef.value[stats[i]]++;
            dummyVillageRef.value[statExp[i]] -= dummyVillageRef.value[statExpNeeded[i]];
        }
    }
    dummyVillageRef.value.experience += getRandomIntBetween(50, 150) * task?.difficulty;
    if (dummyVillageRef.value.experience > dummyVillageRef.value.level_exp_needed) {
        dummyVillageRef.value.level++;
        dummyVillageRef.value.experience -= dummyVillageRef.value.level_exp_needed;
    }
    dummyVillageRef.value.coins += getRandomIntBetween(150, 250) * task?.difficulty;
}

function getRandomIntBetween(min: number, max: number) {
    const top = max - min;
    return Math.floor(Math.random() * top) + min;
}

export type DummyTaskList = {
    id: number;
    name: string;
    tasks: Task[];
}

export type DummyVillageStats = {
    economy: number;
    economy_exp: number;
    economy_exp_needed: number;
    labour: number;
    labour_exp: number;
    labour_exp_needed: number;
    craft: number;
    craft_exp: number;
    craft_exp_needed: number;
    art: number;
    art_exp: number;
    art_exp_needed: number;
    community: number;
    community_exp: number;
    community_exp_needed: number;
}

type DummyVillageKeys = keyof DummyVillageStats;