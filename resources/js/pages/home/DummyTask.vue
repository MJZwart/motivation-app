<template>
    <div>
        <div class="task-sidebar" :class="`diff-${task.difficulty}`" />
        <div class="task-content">
            <p class="task-title d-flex">
                <Tooltip :text="$t('complete-task')">
                    <Icon 
                        :icon="CHECK_SQUARE"
                        class="complete-icon check-square-icon green"
                        @click="completeTask(task)" />
                </Tooltip>
        
                {{task.name}}             
                <span class="ml-auto">
                    <Tooltip :text="$t('new-sub-task')">
                        <Icon 
                            :icon="CREATE"
                            class="create-icon green"
                            @click="$emit('newSubTask', task)" />
                    </Tooltip>
                    <Tooltip :text="$t('edit-task')">
                        <Icon 
                            :icon="EDIT"
                            class="edit-icon"
                            @click="$emit('editTask', task)" />
                    </Tooltip>
                    <Tooltip :text="$t('delete-task')">
                        <Icon 
                            :icon="TRASH"
                            class="delete-icon red"
                            @click="$emit('deleteTask', task)" />
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
                                @click="completeSubTask(subTask)"
                            />
                        </Tooltip>
                        {{subTask.name}}
                        <span class="ml-auto">
                            <Tooltip :text="$t('edit-sub-task')">
                                <Icon 
                                    :icon="EDIT"
                                    class="edit-icon"
                                    @click="$emit('editTask', subTask)" />
                            </Tooltip>
                            <Tooltip :text="$t('delete-sub-task')">
                                <Icon 
                                    :icon="TRASH"
                                    class="delete-icon red"
                                    @click="$emit('deleteTask', subTask)" />
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
import type {Task} from 'resources/types/task';
import {ARROW_DOWN_RIGHT, CHECK_SQUARE, CREATE, EDIT, TRASH} from '/js/constants/iconConstants';
import {completeTask, completeSubTask} from './homepageService'

defineProps<{task: Task}>();
defineEmits(['newSubTask', 'editTask', 'deleteTask']);
</script>