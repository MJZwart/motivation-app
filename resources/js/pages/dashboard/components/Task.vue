<template>
    <div :class="{'task-hover': hover}" @mouseover="hover = true" @mouseleave="hover = false">
        <div class="task-sidebar" :class="`diff-${task.difficulty}`" />
        <div class="task-content">
            <p class="task-title d-flex">
                <Tooltip :text="$t('complete-task')" placement="right">
                    <Icon 
                        :icon="CHECK_SQUARE"
                        class="complete-icon check-square-icon green"
                        @click="completeTask(task)" />
                </Tooltip>
                {{task.name}}
                <span class="ml-auto">
                    <Tooltip v-if="task.repeatable != 'NONE'" :text="$t(task.repeatable)">
                        <Icon 
                            :icon="REPEAT"
                            class="repeat-icon" />
                    </Tooltip>
                    <Tooltip :text="$t('new-sub-task')">
                        <Icon 
                            :icon="CREATE"
                            class="create-icon green"
                            @click="openNewTask(task)" />
                    </Tooltip>
                    <Tooltip :text="$t('edit-task')">
                        <Icon 
                            :icon="EDIT"
                            class="edit-icon"
                            @click="$emit('editTask', {task: task})" />
                    </Tooltip>
                    <Tooltip :text="$t('delete-task')">
                        <Icon 
                            :icon="TRASH"
                            class="delete-icon red"
                            @click="deleteTask(task)" />
                    </Tooltip>
                </span>
                
            </p>
            
            <p class="task-description">{{task.description}}</p>

            <div v-for="subTask in task.tasks" :key="subTask.id" class="sub-task">
                <div class="subtask-sidebar task-sidebar" :class="`diff-${subTask.difficulty}`" />
                <div class="subtask-content">
                    <p class="task-title d-flex">
                        <Icon :icon="ARROW_DOWN_RIGHT" rotation="90" />
                        <Tooltip :text="$t('complete-sub-task')" class="ml-1">
                            <Icon 
                                :icon="CHECK_SQUARE"
                                class="complete-icon check-square-icon green"
                                @click="completeTask(subTask)" />
                        </Tooltip>
                        {{subTask.name}}
                        <span class="ml-auto">
                            <Tooltip v-if="subTask.repeatable != 'NONE'" :text="$t(subTask.repeatable)">
                                <Icon 
                                    :icon="REPEAT"
                                    class="repeat-icon" />
                            </Tooltip>
                            <Tooltip :text="$t('edit-sub-task')">
                                <Icon 
                                    :icon="EDIT"
                                    class="edit-icon"
                                    @click="$emit('editTask', {task: subTask, superTaskParam: task})" />
                            </Tooltip>
                            <Tooltip :text="$t('delete-sub-task')">
                                <Icon 
                                    :icon="TRASH"
                                    class="delete-icon red"
                                    @click="deleteTask(subTask)" />
                            </Tooltip>
                        </span>
                    </p>
                    <p class="task-description">{{subTask.description}}</p>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
import type {Task, TaskList} from 'resources/types/task';
import {ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import {useI18n} from 'vue-i18n';
import {CREATE, EDIT, REPEAT, TRASH, ARROW_DOWN_RIGHT, CHECK_SQUARE} from '/js/constants/iconConstants';
const {t} = useI18n();

defineProps<{task: Task, taskList: TaskList}>();

const emit = defineEmits(['newTask', 'editTask']);

const hover = ref(false);

function openNewTask(task: Task) {
    emit('newTask', task);
}

const taskStore = useTaskStore();

function deleteTask(task: Task) {
    const confirmationText = t('confirmation-delete-task', [task.name]);
    if (confirm(confirmationText)) {
        taskStore.deleteTask(task.id);
    }
}
function completeTask(task: Task) {
    if (task.tasks && task.tasks.length > 0 && !confirm(t('complete-sub-task-confirmation')))
        return;
    taskStore.completeTask(task.id);
}
</script>

<style lang="scss" scoped>
.task-hover {
    background-color: var(--background-darker);
}
</style>