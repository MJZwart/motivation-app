<template>
    <div>
        <Summary :footer="true" class="p-0">
            <template #header>
                <span class="d-flex">
                    {{taskList.name}}
                    <span class="ml-auto">
                        <Tooltip :text="$t('edit-task-list')">
                            <FaIcon 
                                :icon="['far', 'pen-to-square']"
                                class="icon white small"
                                @click="showEditTaskList()" />
                        </Tooltip>
                        <Tooltip :text="$t('delete-task-list')">
                            <FaIcon 
                                icon="trash"
                                class="icon small white"
                                @click="showDeleteTaskList(task)" />
                        </Tooltip>
                    </span>
                </span>
            </template>
            <slot>
                <template v-for="(task, index) in taskList.tasks" :key="task.id">
                    <Task 
                        :task="task" 
                        :class="taskClass(index)"
                        v-on:newTask="openNewTask" />
                </template>
            </slot>
            <template #footer>           
                <button class="block clear bottom-radius p-0" @click="openNewTask(null)">
                    <Tooltip :text="$t('add-new-task')">
                        <FaIcon 
                            icon="square-plus"
                            class="icon large green m-0 wide" />
                    </Tooltip>
                </button>
            </template>
        </Summary>

        <Modal :show="showNewTaskModal" :footer="false" :title="$t('new-task')" @close="closeNewTask">
            <NewTask :superTask="superTask.value" :taskList="taskList" @close="closeNewTask" />
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


<script setup>
import Tooltip from '../bootstrap/Tooltip.vue';
import Task from './Task.vue';
import Summary from '../summary/Summary.vue';
import EditTaskList from '../modals/EditTaskList.vue';
import DeleteTaskListConfirm from '../modals/DeleteTaskListConfirm.vue';
import {reactive, ref} from 'vue';
import {useMainStore} from '@/store/store';
import NewTask from '../modals/NewTask.vue';

const props = defineProps({
    taskList: {
        /** @type {import('resources/types/task').TaskList} */
        type: Object,
        required: true,
    },
});

/** @type {import('../../types/task').Task | null} */
const superTask = reactive({});
const showNewTaskModal = ref(false);
const showEditTaskListModal = ref(false);
const showDeleteTaskListConfirmModal = ref(false);

/** @type {import('../../types/task').TaskList | null} */
const taskListToEdit = ref({});
/** @type {import('../../types/task').TaskList | null} */
const taskListToDelete = ref({});

const mainStore = useMainStore();

function openNewTask(superTaskToSet) {
    mainStore.clearErrors();
    superTask.value = superTaskToSet;
    showNewTaskModal.value = true;
}
function closeNewTask() {
    showNewTaskModal.value = false;
}

/** Shows and hides the modal to edit a given task list
 * @param {import('../../types/task').TaskList} taskList
 */
function showEditTaskList() {
    mainStore.clearErrors();
    taskListToEdit.value = props.taskList;
    showEditTaskListModal.value = true;
}
function closeEditTaskList() {
    showEditTaskListModal.value = false;
}

/** Shows and hides the modal to confirm deleting a task list
 * @param {import('../../types/task').TaskList} taskList
 */
function showDeleteTaskList() {
    mainStore.clearErrors();
    taskListToDelete.value = props.taskList;
    showDeleteTaskListConfirmModal.value = true;
}
function closeDeleteTaskList() {
    showDeleteTaskListConfirmModal.value = false;
}

function taskClass(index) {
    return index == props.taskList.tasks.length -1 ? 'task-last' : 'task';
}
</script>

<style lang="scss">
.p-0 {
    .card-body {
        padding: 0 !important;
        min-height: 0;
    }
}
</style>