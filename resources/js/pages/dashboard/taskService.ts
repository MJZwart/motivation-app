import type {NewTask, NewTaskList, Template} from 'resources/types/task';
import {ref} from 'vue';

export const templates = ref<Template[]>([]);

export function getNewTask(taskListId: number): NewTask {
    return {
        name: '',
        description: '',
        difficulty: 3,
        type: 'GENERIC',
        repeatable: 'NONE',
        task_list_id: taskListId,
    }
}
export function getNewTaskList(): NewTaskList {
    return {
        name: '',
    }
}