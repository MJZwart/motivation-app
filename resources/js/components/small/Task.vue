<template>
    <div>
        <p class="task-title d-flex">
            <Tooltip :text="$t('complete-task')">
                <FaIcon 
                    :icon="['far', 'square-check']"
                    class="icon small green"
                    @click="completeTask(task)" />
            </Tooltip>
            {{task.name}}             
            <span class="ml-auto">
                <Tooltip :text="$t('new-sub-task')">
                    <FaIcon 
                        icon="square-plus"
                        class="icon small green"
                        @click="openNewTask(task)" />
                </Tooltip>
                <Tooltip :text="$t('edit-task')">
                    <FaIcon 
                        :icon="['far', 'pen-to-square']"
                        class="icon small"
                        @click="editTask(task)" />
                </Tooltip>
                <Tooltip :text="$t('delete-task')">
                    <FaIcon 
                        icon="trash"
                        class="icon small red"
                        @click="deleteTask(task)" />
                </Tooltip>
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>

        <div v-for="subTask in task.tasks" :key="subTask.id" class="sub-task">
            <p class="task-title d-flex">
                <FaIcon icon="arrow-turn-up" rotation="90" />
                <Tooltip :text="$t('complete-sub-task')">
                    <FaIcon 
                        :icon="['far', 'square-check']"
                        class="icon small green"
                        @click="completeTask(subTask)" />
                </Tooltip>
                {{subTask.name}}
                <Tooltip :text="$t('edit-sub-task')" class="ml-auto">
                    <FaIcon 
                        :icon="['far', 'pen-to-square']"
                        class="icon small"
                        @click="editTask(subTask)" />
                </Tooltip>
                <Tooltip :text="$t('delete-sub-task')">
                    <FaIcon 
                        icon="trash"
                        class="icon small red"
                        @click="deleteTask(subTask)" />
                </Tooltip>
            </p>
            <p class="task-description">{{subTask.description}}</p>
        </div>
    </div>
</template>


<script setup>
import {useTaskStore} from '@/store/taskStore';
import {useI18n} from 'vue-i18n'

const {t} = useI18n() // use as global scope

defineProps({
    task: {
        /** @type {import('resources/types/task').Task} */
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['newTask', 'editTask']);

/** @param {import('resources/types/task').Task} task */
function openNewTask(task) {
    emit('newTask', task);
}
/** @param {import('resources/types/task').Task} task */
function editTask(task) {
    emit('editTask', task);
}

const taskStore = useTaskStore();
/** @param {import('resources/types/task').Task} task */
function deleteTask(task) {
    const confirmationText = t('confirmation-delete-task', [task.name]);
    if (confirm(confirmationText)) {
        taskStore.deleteTask(task);
    }
}
/** @param {import('resources/types/task').Task} task */
function completeTask(task) {
    if (task.tasks.length > 0 && !confirm(t('complete-sub-task-confirmation')))
        return;
    taskStore.completeTask(task);
}
</script>