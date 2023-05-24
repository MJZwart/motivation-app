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
                <TaskComp :task="task" :taskList="taskList" :class="taskClass(index)" v-on:newTask="openNewTask" />
            </template>
            <button class="block bottom-radius p-0 new-task" @click="openNewTask()">
                {{ $t('new-task') }}
            </button>
        </ContentBlock>

        <Modal :show="showNewTaskModal" :title="$t('new-task')" @close="closeNewTask">
            <NewTask :superTask="superTask" :taskList="taskList" @close="closeNewTask" />
        </Modal>
        <Modal :show="showEditTaskListModal" :title="$t('edit-task-list')" @close="closeEditTaskList">
            <EditTaskList v-if="taskListToEdit" :taskList="taskListToEdit" @close="closeEditTaskList" />
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
import NewTask from './NewTask.vue';
import EditTaskList from './EditTaskList.vue';
import DeleteTaskListConfirm from './DeleteTaskListConfirm.vue';
import {ref} from 'vue';
import {useMainStore} from '/js/store/store';
import type {TaskList, Task} from 'resources/types/task';
import {EDIT, TRASH} from '/js/constants/iconConstants';
import {formModal} from '/js/components/modal/modalService';
import {useTaskStore} from '/js/store/taskStore';

const props = defineProps<{taskList: TaskList}>();

const superTask = ref<Task | null>(null);
const showNewTaskModal = ref(false);
const showEditTaskListModal = ref(false);
const showDeleteTaskListConfirmModal = ref(false);

const taskListToEdit = ref<TaskList | null>(null);
const taskListToDelete = ref<TaskList | null>(null);

const mainStore = useMainStore();
const taskStore = useTaskStore();

function openNewTask(superTaskToSet: Task | null = null) {
    mainStore.clearErrors();
    superTask.value = superTaskToSet;
    showNewTaskModal.value = true;
}
function closeNewTask() {
    showNewTaskModal.value = false;
}

/**
 * Shows and hides the modal to edit a given task list
 */
function showEditTaskList() {
    mainStore.clearErrors();
    formModal(props.taskList, EditTaskList, submitEditTaskList, 'Edit task list');
    // taskListToEdit.value = props.taskList;
    // showEditTaskListModal.value = true;
}
async function submitEditTaskList(editedTaskList: TaskList) {
    await taskStore.updateTaskList(editedTaskList)
}
function closeEditTaskList() {
    showEditTaskListModal.value = false;
}

/**
 * Shows and hides the modal to confirm deleting a task list
 */
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
