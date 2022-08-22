<template>
    <div>
        <Summary :footer="true" class="p-0">
            <template #header>
                <span class="d-flex">
                    {{taskList.name}}
                    <span class="ml-auto">
                        <Tooltip :text="$t('edit-task-list')" placement="bottom">
                            <FaIcon 
                                :icon="['far', 'pen-to-square']"
                                class="icon white small"
                                @click="showEditTaskList()" />
                        </Tooltip>
                        <Tooltip :text="$t('delete-task-list')" placement="bottom">
                            <FaIcon 
                                icon="trash"
                                class="icon small white"
                                @click="showDeleteTaskList()" />
                        </Tooltip>
                        <Tutorial tutorial="TaskList" colorVariant="white" />
                    </span>
                </span>
            </template>
            <slot>
                <template v-for="(task, index) in taskList.tasks" :key="task.id">
                    <TaskComp 
                        :task="task" 
                        :class="taskClass(index)"
                        v-on:newTask="openNewTask" />
                </template>
            </slot>
            <template #footer>           
                <button class="block bottom-radius p-0 new-task" @click="openNewTask()">
                    {{$t('new-task')}}
                </button>
            </template>
        </Summary>

        <Modal :show="showNewTaskModal" :footer="false" :title="$t('new-task')" @close="closeNewTask">
            <NewTask :superTask="superTask" :taskList="taskList" @close="closeNewTask" />
        </Modal>
        <Modal :show="showEditTaskListModal" :footer="false" :title="$t('edit-task-list')" @close="closeEditTaskList">
            <EditTaskList :taskList="taskListToEdit" @close="closeEditTaskList" />
        </Modal>
        <Modal 
            :show="showDeleteTaskListConfirmModal" 
            :footer="false" 
            :title="$t('delete-task-list-confirm')" 
            @close="closeDeleteTaskList">
            <DeleteTaskListConfirm :taskList="taskListToDelete" @close="closeDeleteTaskList" />
        </Modal>
    </div>
</template>


<script setup lang="ts">
import TaskComp from './Task.vue';
import NewTask from './NewTask.vue';
import Summary from '/js/components/global/Summary.vue';
import EditTaskList from './EditTaskList.vue';
import DeleteTaskListConfirm from './DeleteTaskListConfirm.vue';
import {ref} from 'vue';
import {useMainStore} from '/js/store/store';
import type {TaskList, Task} from 'resources/types/task';

const props = defineProps<{taskList: TaskList}>();

const superTask = ref<Task | null>(null);
const showNewTaskModal = ref(false);
const showEditTaskListModal = ref(false);
const showDeleteTaskListConfirmModal = ref(false);

const taskListToEdit = ref<TaskList | null>(null);
const taskListToDelete = ref<TaskList | null>(null);

const mainStore = useMainStore();

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
    taskListToEdit.value = props.taskList;
    showEditTaskListModal.value = true;
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
    return index == props.taskList.tasks.length -1 ? 'task-last' : 'task';
}
</script>

<style lang="scss">
@import '../.././../../assets/scss/variables';
.p-0 {
    .card-body {
        padding: 0 !important;
        min-height: 0;
    }
}
.new-task {
    background-color: $green;
    height: 3rem;
    margin-bottom: 0;
    border: none;
}
.new-task:hover {
    background-color: $dark-green;
}
</style>