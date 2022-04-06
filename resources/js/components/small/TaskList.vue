<template>
    <div>
        <Summary :footer="true" class="p-0">
            <template #header>
                <span class="d-flex">
                    {{taskList.name}}
                    <span class="ml-auto">
                        <b-icon-pencil-square 
                            :id="'edit-task-list-' + taskList.id"
                            class="icon white small"
                            @click="editTaskList()" />
                        <b-tooltip :target="'edit-task-list-' + taskList.id">{{ $t('edit-task-list') }}</b-tooltip>
                        <b-icon-trash 
                            :id="'delete-task-list-' + taskList.id"
                            class="icon white small"
                            @click="deleteTaskList()" />
                        <b-tooltip :target="'delete-task-list-' + taskList.id">{{ $t('delete-task-list') }}</b-tooltip>
                    </span>
                </span>
            </template>
            <slot>
                <template v-for="(task, index) in taskList.tasks">
                    <Task 
                        :key="task.id" 
                        :task="task" 
                        :class="taskClass(index)"
                        v-on:newTask="openNewTask"
                        v-on:editTask="editTask" />
                </template>
            </slot>
            <template #footer>           
                <button class="block clear bottom-radius p-0" @click="openNewTask(null)">
                    <b-icon-plus-square-fill 
                        :id="'add-new-task-' + taskList.id" 
                        class="icon large green m-0 wide" 
                    />
                    <b-tooltip :target="'add-new-task-' + taskList.id">{{ $t('add-new-task') }}</b-tooltip>
                </button>
            </template>
        </Summary>
    </div>
</template>


<script>
import Task from './Task.vue';
import Summary from '../summary/Summary.vue';
export default {
    components: {Task, Summary},
    props: {
        taskList: {
            /** @type {import('resources/types/task').TaskList} */
            type: Object,
            required: true,
        },
    },
    methods: {
        /** @param {import('resources/types/task').Task} */
        openNewTask(superTask) {
            this.$emit('newTask', superTask, this.taskList);
        },
        /** @param {import('resources/types/task').Task} */
        editTask(task) {
            this.$emit('editTask', task);
        },
        editTaskList() {
            this.$emit('editTaskList', this.taskList);
        },
        deleteTaskList() {
            this.$emit('deleteTaskList', this.taskList);
        },
        taskClass(index) {
            return index == this.taskList.tasks.length -1 ? 'task-last' : 'task';
        },
    },
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