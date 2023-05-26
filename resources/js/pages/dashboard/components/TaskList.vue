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

        <Modal :show="showNewTaskModal" :title="$t('new-task')" @close="closeNewTask">
            <CreateEditTask 
                :task="getNewTask(taskList.id)" 
                :task-list="taskList" 
                :super-task="superTask" 
                @submit="createTask" 
                @close="closeNewTask" />
        </Modal>        
        <Modal :show="showEditTaskModal" :title="$t('edit-task')" @close="closeEditTask">
            <CreateEditTask 
                :task="taskToEdit" 
                :task-list="taskList" 
                :super-task="superTask"
                @submit="editTask" 
                @close="closeEditTask" />
        </Modal>
        <Modal
            :show="showDeleteTaskListConfirmModal"
            :footer="false"
            :title="$t('delete-task-list-confirm')"
            @close="closeDeleteTaskList"
        >
            <DeleteTaskListConfirm v-if="taskListToDelete" :taskList="taskListToDelete" @close="closeDeleteTaskList" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import TaskComp from './Task.vue';
import EditTaskList from './EditTaskList.vue';
import DeleteTaskListConfirm from './DeleteTaskListConfirm.vue';
import {ref} from 'vue';
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
const showNewTaskModal = ref(false);
const superTask = ref<Task | null>(null);
function openNewTask(superTaskToSet: Task | null = null) {
    mainStore.clearErrors();
    superTask.value = superTaskToSet;
    showNewTaskModal.value = true;
}
async function createTask(task: NewTask) {
    await taskStore.storeTask(task);
    closeNewTask();
}
function closeNewTask() {
    showNewTaskModal.value = false;
}

/**
 * Edit task
 */
const taskToEdit = ref<Task | null>(null);
const showEditTaskModal = ref(false);
function openEditTask({task, superTaskParam}: {task: Task, superTaskParam: Task | null}) {
    mainStore.clearErrors();
    taskToEdit.value = task;
    if (superTaskParam) superTask.value = superTaskParam;
    showEditTaskModal.value = true;
}
async function editTask(task: Task) {
    await taskStore.updateTask(task);
    closeEditTask();
}
function closeEditTask() {
    showEditTaskModal.value = false;
}

/**
 * Shows and hides the modal to edit a given task list
 */
function showEditTaskList() {
    mainStore.clearErrors();
    formModal(props.taskList, EditTaskList, submitEditTaskList, 'Edit task list');
}
async function submitEditTaskList(editedTaskList: TaskList) {
    await taskStore.updateTaskList(editedTaskList)
}

/**
 * Shows and hides the modal to confirm deleting a task list
 */
const showDeleteTaskListConfirmModal = ref(false);
const taskListToDelete = ref<TaskList | null>(null);
function showDeleteTaskList() {
    mainStore.clearErrors();
    taskListToDelete.value = props.taskList;
    showDeleteTaskListConfirmModal.value = true;
}
function closeDeleteTaskList() {
    showDeleteTaskListConfirmModal.value = false;
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
