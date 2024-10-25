<template>
    <div class="dummy-tasks">
        <ContentBlock customClass="p-0">
            <template #header>
                <span class="d-flex pl-3 pt-3 pr-3">
                    <h4>{{ taskList.name }}</h4>
                    <span class="ml-auto">
                        <Tooltip :text="$t('edit-task-list')">
                            <Icon 
                                :icon="EDIT"
                                class="edit-icon small"
                                @click="editTaskList" />
                        </Tooltip>
                        <Tooltip :text="$t('delete-task-list')">
                            <Icon 
                                :icon="TRASH"
                                class="delete-icon small" />
                        </Tooltip>
                    </span>
                </span>
            </template>
            <template v-for="(task, index) in taskList.tasks" :key="task.id" >
                <TaskVue 
                    :task="task" 
                    :class="taskClass(index)"
                    @newSubTask="newSubTask"
                    @editTask="editTask"
                    @deleteTask="deleteTask" />
            </template>
            <button class="block bottom-radius p-0 new-task" variant="outline" @click="newTask">
                {{$t('new-task')}}
            </button>
        </ContentBlock>
    </div>
</template>


<script setup lang="ts">
import type {Task} from 'resources/types/task';
import TaskVue from './DummyTask.vue';
import {EDIT, TRASH} from '/js/constants/iconConstants';
import {DummyTaskList, deleteTask} from './homepageService';
import {formModal} from '/js/components/modal/modalService';
import {getNewTask} from '/js/services/taskService';
import CreateEditTask from '/js/pages/dashboard/components/CreateEditTask.vue';
import {submitSubTask, submitTask, submitEditTask, submitEditTaskList} from './homepageService';
import CreateEditTaskList from '/js/pages/dashboard/components/CreateEditTaskList.vue';

const props = defineProps<{taskList: DummyTaskList}>();

function taskClass(index: number) {
    return index == props.taskList.tasks.length -1 ? 'task task-last' : 'task';
}

function newSubTask(task: Task) {
    formModal({
        task: getNewTask(props.taskList.id),
        taskList: props.taskList,
        superTask: task,
    }, CreateEditTask, 
    submitSubTask, 
    'new-sub-task');
}
function newTask() {
    formModal({
        task: getNewTask(props.taskList.id),
        taskList: props.taskList,
    }, CreateEditTask,
    submitTask,
    'new-task');
}
function editTask(task: Task) {
    formModal({
        task: task,
        taskList: props.taskList,
        superTask: task.super_task_id ?? undefined,
    }, CreateEditTask,
    submitEditTask,
    'edit-task');
}
function editTaskList() {
    formModal(props.taskList, CreateEditTaskList, submitEditTaskList, 'edit-task-list');
}
</script>

<style lang="scss">
.p-0 {
    .card-body {
        padding: 0 !important;
        min-height: 0;
    }
}
.dummy-tasks {
    box-shadow: 0 0.125rem 0.5rem rgb(0 0 0 / 75%);
    border-radius: 0.5rem;
}
.new-task {
    background-color: var(--action-button);
    height: 3rem;
    margin-bottom: 0;
    border: none;
    margin-top: -2px;
}
</style>