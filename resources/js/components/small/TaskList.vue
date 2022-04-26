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
                                @click="editTaskList()" />
                        </Tooltip>
                        <Tooltip :text="$t('delete-task-list')">
                            <FaIcon 
                                icon="trash"
                                class="icon small white"
                                @click="deleteTaskList(task)" />
                        </Tooltip>
                    </span>
                </span>
            </template>
            <slot>
                <template v-for="(task, index) in taskList.tasks" :key="task.id">
                    <Task 
                        :task="task" 
                        :class="taskClass(index)"
                        v-on:newTask="openNewTask"
                        v-on:editTask="editTask" />
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
        <Modal :show="showEditTaskModal" :footer="false" :title="$t('edit-task')" @close="closeEditTask">
            <EditTask :task="taskToEdit" @close="closeEditTask" />
        </Modal>
    </div>
</template>


<script setup>
import Tooltip from '../bootstrap/Tooltip.vue';
import Task from './Task.vue';
import Summary from '../summary/Summary.vue';
import {reactive, ref} from 'vue';
import {useMainStore} from '@/store/store';
import NewTask from '../modals/NewTask.vue';
import EditTask from '../modals/EditTask.vue';

const props = defineProps({
    taskList: {
        /** @type {import('resources/types/task').TaskList} */
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['editTaskList', 'deleteTaskList']);

/** @type {import('../../types/task').Task | null} */
const superTask = reactive({});
/** @type {import('../../types/task').Task | null} */
const taskToEdit = reactive({});

const showNewTaskModal = ref(false);
const showEditTaskModal = ref(false);

const mainStore = useMainStore();

function openNewTask(superTaskToSet) {
    mainStore.clearErrors();
    superTask.value = superTaskToSet;
    showNewTaskModal.value = true;
}
function closeNewTask() {
    showNewTaskModal.value = false;
}

/** Shows and hides the modal to edit a given task
 * @param {import('../../types/task').Task} task
 */
function editTask(task) {
    mainStore.clearErrors();
    taskToEdit.value = task;
    showEditTaskModal.value = true;
}
function closeEditTask() {
    showEditTaskModal.value = false;
}

function editTaskList() {
    emit('editTaskList', props.taskList);
}
function deleteTaskList() {
    emit('deleteTaskList', props.taskList);
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