<template>
    <div>
        <ContentBlock customClass="p-0">
            <template #header>
                <span class="d-flex pl-3 pt-3 pr-3">
                    <h4>{{ taskList.name }}</h4>
                    <span class="ml-auto">
                        <Tooltip :text="$t('edit-task-list')" placement="bottom">
                            <Icon
                                :icon="EDIT"
                                class="edit-icon small"
                                @click="showEditTaskList()"
                            />
                        </Tooltip>
                        <Tooltip :text="$t('delete-task-list')" placement="bottom">
                            <Icon :icon="TRASH" class="delete-icon small" @click="showDeleteTaskList()" />
                        </Tooltip>
                        <Tutorial tutorial="task-list" />
                    </span>
                </span>
            </template>
            <template v-for="(task, index) in taskList.tasks" :key="task.id">
                <TaskComp 
                    :task="task" 
                    :taskList="taskList" 
                    :class="taskClass(index)" 
                    v-on:newTask="openNewTask" 
                    @editTask="openEditTask" />
            </template>
            <button class="block bottom-radius p-0 new-task" @click="openNewTask()">
                {{ $t('new-task') }}
            </button>
        </ContentBlock>
    </div>
</template>

<script setup lang="ts">
import TaskComp from './Task.vue';
import EditTaskList from './EditTaskList.vue';
import DeleteTaskListConfirm from './DeleteTaskListConfirm.vue';
import {useMainStore} from '/js/store/store';
import type {TaskList, Task, NewTask} from 'resources/types/task';
import {EDIT, TRASH} from '/js/constants/iconConstants';
import {formModal} from '/js/components/modal/modalService';
import CreateEditTask from './CreateEditTask.vue';
import {getNewTask} from '../taskService';
import {useTaskStore} from '/js/store/taskStore';

const props = defineProps<{taskList: TaskList}>();

const mainStore = useMainStore();
const taskStore = useTaskStore();

/** 
 * New task 
 */
function openNewTask(superTaskToSet: Task | null = null) {
    mainStore.clearErrors();
    formModal(
        {task: getNewTask(props.taskList.id), taskList: props.taskList, superTask: superTaskToSet},
        CreateEditTask,
        createTask,
        'new-task',
    );
}
async function createTask({task}: {task:NewTask}) {
    await taskStore.storeTask(task);
}

/**
 * Edit task
 */
function openEditTask({task, superTaskParam}: {task: Task, superTaskParam: Task | null}) {
    mainStore.clearErrors();
    formModal(
        {task: task, taskList: props.taskList, superTask: superTaskParam},
        CreateEditTask,
        editTask,
        'edit-task',
    );
}
async function editTask({task}: {task: Task}) {
    await taskStore.updateTask(task);
}

/**
 * Shows and hides the modal to edit a given task list
 */
function showEditTaskList() {
    mainStore.clearErrors();
    formModal(props.taskList, EditTaskList, submitEditTaskList, 'edit-task-list');
}
async function submitEditTaskList(editedTaskList: TaskList) {
    await taskStore.updateTaskList(editedTaskList)
}

/**
 * Shows and hides the modal to confirm deleting a task list
 */
function showDeleteTaskList() {
    mainStore.clearErrors();
    // @ts-ignore This is due to the setup of the form modal expecting the submitEvent's param type
    formModal(props.taskList, DeleteTaskListConfirm, submitDeleteTaskList, 'delete-task-list-confirm');
}
async function submitDeleteTaskList(data: {option: string | number, tasks: Task[]}) {
    if (data.option != 'delete') {
        const mergeData = {taskListId: data.option, tasks: data.tasks};
        await taskStore.mergeTasks(mergeData);
    }
    await taskStore.deleteTaskList(props.taskList.id);
}

function taskClass(index: number) {
    return index == props.taskList.tasks.length - 1 ? 'task task-last' : 'task';
}
</script>

<style lang="scss">
.p-0 {
    .card-body {
        padding: 0 !important;
        min-height: 0;
    }
}
.new-task {
    background-color: var(--action-button);
    height: 3rem;
    margin-bottom: 0;
    border: none;
    margin-top: -2px;
}
.new-task:hover {
    background-color: var(--action-button-hover);
    border-color: var(--action-button-hover);
}
</style>
