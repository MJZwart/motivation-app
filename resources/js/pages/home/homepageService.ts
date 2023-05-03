import {successToast} from '/js/services/toastService';
import {ref} from 'vue';
import {DUMMY_CHARACTER, DUMMY_TASK_LIST} from '/js/constants/dummyConstants';

export const dummyCharacterRef = ref(Object.assign({}, DUMMY_CHARACTER));

export const dummyTaskListRef = ref(Object.assign({}, DUMMY_TASK_LIST));

export function completeTask(taskId: number) {
    const taskIndex = dummyTaskListRef.value.tasks.findIndex(task => task.id === taskId);
    calculateReward(taskId);
    dummyTaskListRef.value.tasks.splice(taskIndex, 1);
    successToast('That\'s how you complete tasks! Log in to manage your own tasks.');
}

const stats = ['a', 'b', 'c', 'd', 'e'];
const statExp = ['a_exp', 'b_exp', 'c_exp', 'd_exp', 'e_exp'];
const statExpNeeded = ['a_exp_needed', 'b_exp_needed', 'c_exp_needed', 'd_exp_needed', 'e_exp_needed'];

function calculateReward(taskId: number) {
    const task = dummyTaskListRef.value.tasks.find(task => task.id === taskId);
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