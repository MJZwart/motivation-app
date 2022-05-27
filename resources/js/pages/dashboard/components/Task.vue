<template>
    <div>
        <p class="task-title d-flex">
            <Tooltip :text="$t('complete-task')" placement="right">
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
        
        <Modal :show="showEditTaskModal" :footer="false" :title="$t('edit-task')" @close="closeEditTask">
            <EditTask :task="taskToEdit" @close="closeEditTask" />
        </Modal>
    </div>
</template>


<script setup>
import {reactive, ref} from 'vue';
import EditTask from './EditTask.vue';
import {useTaskStore} from '/js/store/taskStore';
import {useMainStore} from '/js/store/store';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope
const mainStore = useMainStore();

defineProps({
    task: {
        /** @type {import('resources/types/task').Task} */
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['newTask']);

/** @type {import('../../types/task').Task | null} */
const taskToEdit = reactive({});
const showEditTaskModal = ref(false);

/** @param {import('resources/types/task').Task} task */
function openNewTask(task) {
    emit('newTask', task);
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