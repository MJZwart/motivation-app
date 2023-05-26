import {successToast} from '/js/services/toastService';
import {ref} from 'vue';
import {DUMMY_CHARACTER, DUMMY_TASK_LIST, taskId} from '/js/constants/dummyConstants';
import {NewTask, Task, TaskList} from 'resources/types/task';
import {waitingOnResponse} from '/js/services/loadingService';

export const dummyCharacterRef = ref(Object.assign({}, DUMMY_CHARACTER));

export const dummyTaskListRef = ref(Object.assign({}, DUMMY_TASK_LIST));

export function completeTask(task: Task) {
    const taskIndex = dummyTaskListRef.value.tasks.findIndex(foundTask => foundTask.id === task.id);
    if (taskIndex < 0) return;
    calculateReward(dummyTaskListRef.value.tasks[taskIndex]);
    dummyTaskListRef.value.tasks.splice(taskIndex, 1);
    successToast('That\'s how you complete tasks! Log in to manage your own tasks.');
}
export function completeSubTask(task: Task) {
    const superTaskIndex = dummyTaskListRef.value.tasks.findIndex(superTask => superTask.id === task.super_task_id);
    if (superTaskIndex < 0) return;
    const subTaskIndex = dummyTaskListRef.value.tasks[superTaskIndex].tasks?.findIndex(subTask => subTask.id === task.id);
    if (subTaskIndex !== undefined && subTaskIndex < 0) return;
    calculateReward(task);
    // @ts-ignore This is checked above
    dummyTaskListRef.value.tasks[superTaskIndex].tasks.splice(subTaskIndex, 1);
    successToast('That\'s how you complete tasks! Log in to manage your own tasks.');
}

export function submitTask({task}: {task: NewTask}) {
    const taskWithId = {...task, id: taskId.value++, tasks: []};
    dummyTaskListRef.value.tasks.push(taskWithId);
    waitingOnResponse.value = false;
    successToast('That\'s how you make tasks! Note that these tasks won\'t be saved. Log in to create your own lists.');
}

export function submitSubTask({task}: {task: NewTask}) {
    const superTaskIdx = dummyTaskListRef.value.tasks.findIndex(superTask => superTask.id === task.super_task_id);
    const taskWithId = {...task, id: taskId.value++};
    dummyTaskListRef.value.tasks[superTaskIdx].tasks?.push(taskWithId);
    waitingOnResponse.value = false;
    successToast('That\'s how you make tasks! Note that these tasks won\'t be saved. Log in to create your own lists.');
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
    successToast('Example task edited.');
}

export function deleteTask(task: Task) {
    if (task.super_task_id) {
        deleteSubTask(task);
    } else {
        const taskIndex = dummyTaskListRef.value.tasks.findIndex(foundTask => foundTask.id === task.id);
        if (taskIndex !== undefined && taskIndex < 0) return;
        dummyTaskListRef.value.tasks.splice(taskIndex, 1);
    }
    successToast('Task deleted');
}

export function submitEditTaskList(taskList: TaskList) {
    dummyTaskListRef.value.name = taskList.name;
    waitingOnResponse.value = false;
    successToast('Task list edited');
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

const stats = ['a', 'b', 'c', 'd', 'e'];
const statExp = ['a_exp', 'b_exp', 'c_exp', 'd_exp', 'e_exp'];
const statExpNeeded = ['a_exp_needed', 'b_exp_needed', 'c_exp_needed', 'd_exp_needed', 'e_exp_needed'];

function calculateReward(task: Task) {
    if (!task) return;
    for (let i = 0 ; i < stats.length ; i++) {
        dummyCharacterRef.value[statExp[i]] += getRandomIntBetween(25, 75) * task?.difficulty;
        if (dummyCharacterRef.value[statExp[i]] > dummyCharacterRef.value[statExpNeeded[i]]) {
            dummyCharacterRef.value[stats[i]]++;
            dummyCharacterRef.value[statExp[i]] -= dummyCharacterRef.value[statExpNeeded[i]];
        }
    }
    dummyCharacterRef.value.experience += getRandomIntBetween(50, 150) * task?.difficulty;
    if (dummyCharacterRef.value.experience > dummyCharacterRef.value.level_exp_needed) {
        dummyCharacterRef.value.level++;
        dummyCharacterRef.value.experience -= dummyCharacterRef.value.level_exp_needed;
    }
    dummyCharacterRef.value.coins += getRandomIntBetween(150, 250) * task?.difficulty;
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