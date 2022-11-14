<template>
    <div>
        <div class="task-sidebar" :class="`diff-${task.difficulty}`" />
        <div class="task-content">
            <p class="task-title d-flex">
                <Tooltip :text="$t('complete-task')" placement="right">
                    <FaIcon 
                        :icon="['far', 'square-check']"
                        class="icon medium green"
                        @click="completeTask(task)" />
                </Tooltip>
                {{task.name}}
                <span class="ml-auto">
                    <Tooltip v-if="task.repeatable != 'NONE'" :text="$t(task.repeatable)">
                        <FaIcon 
                            icon="repeat"
                            class="icon small" />
                    </Tooltip>
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
                <div class="subtask-sidebar task-sidebar" :class="`diff-${subTask.difficulty}`" />
                <div class="subtask-content">
                    <p class="task-title d-flex">
                        <FaIcon icon="arrow-turn-up" rotation="90" />
                        <Tooltip :text="$t('complete-sub-task')" class="ml-1">
                            <FaIcon 
                                :icon="['far', 'square-check']"
                                class="icon medium green"
                                @click="completeTask(subTask)" />
                        </Tooltip>
                        {{subTask.name}}
                        <span class="ml-auto">
                            <Tooltip v-if="subTask.repeatable != 'NONE'" :text="$t(subTask.repeatable)">
                                <FaIcon 
                                    icon="repeat"
                                    class="icon small" />
                            </Tooltip>
                            <Tooltip :text="$t('edit-sub-task')">
                                <FaIcon 
                                    :icon="['far', 'pen-to-square']"
                                    class="icon small"
                                    @click="editTask(subTask, task.name)" />
                            </Tooltip>
                            <Tooltip :text="$t('delete-sub-task')">
                                <FaIcon 
                                    icon="trash"
                                    class="icon small red"
                                    @click="deleteTask(subTask)" />
                            </Tooltip>
                        </span>
                    </p>
                    <p class="task-description">{{subTask.description}}</p>
                </div>
            </div>
            
        </div>
        
        <Modal :show="showEditTaskModal" :footer="false" :title="$t('edit-task')" @close="closeEditTask">
            <EditTask 
                v-if="taskToEdit" 
                :task="taskToEdit" 
                :taskList="taskList" 
                :superTask="editSuperTask" 
                @close="closeEditTask" />
        </Modal>
    </div>
</template>


<script setup lang="ts">
import type {Task, TaskList} from 'resources/types/task';
import {ref} from 'vue';
import EditTask from './EditTask.vue';
import {useTaskStore} from '/js/store/taskStore';
import {useMainStore} from '/js/store/store';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope
const mainStore = useMainStore();

defineProps<{task: Task, taskList: TaskList}>();

const emit = defineEmits(['newTask']);

const taskToEdit = ref<Task | null>(null);
const editSuperTask = ref<string | null>(null);
const showEditTaskModal = ref(false);

function openNewTask(task: Task) {
    emit('newTask', task);
}

/** Shows and hides the modal to edit a given task */
function editTask(task: Task, superTask: string | null = null) {
    mainStore.clearErrors();
    editSuperTask.value = superTask;
    taskToEdit.value = task;
    showEditTaskModal.value = true;
}
function closeEditTask() {
    showEditTaskModal.value = false;
}

const taskStore = useTaskStore();
function deleteTask(task: Task) {
    const confirmationText = t('confirmation-delete-task', [task.name]);
    if (confirm(confirmationText)) {
        taskStore.deleteTask(task);
    }
}
function completeTask(task: Task) {
    if (task.tasks && task.tasks.length > 0 && !confirm(t('complete-sub-task-confirmation')))
        return;
    taskStore.completeTask(task);
}
</script>

<style lang="scss" scoped>
.number-icon {
    font-family: var(--border-color);
    margin: 1px;
    position: relative;
    bottom: 2px;
}
</style>