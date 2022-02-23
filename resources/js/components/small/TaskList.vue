<template>
    <div>
        <span class="card-title d-flex">{{taskList.name}}
            <span class="ml-auto">
                <b-icon-pencil-square 
                    class="icon small"
                    @click="editTaskList()" />
                <b-icon-trash 
                    class="icon small"
                    @click="deleteTaskList()" />
            </span>

        </span>
        <template v-for="task in taskList.tasks">

            <task 
                :key="task.id" 
                :task="task" 
                class="task side-border" 
                v-on:newTask="openNewTask"
                v-on:editTask="editTask" />

        </template>
        <b-button block class="bottom-radius" @click="openNewTask(null)">{{ $t('add-new-task') }}</b-button>
    </div>
</template>


<script>
import Task from './Task.vue';
export default {
    components: {Task},
    props: {
        taskList: {
            /** @type {import('resources/types/task').TaskList} */
            type: Object,
            required: true,
        },
    },
    methods: {
        /** @param {import('resources/types/task').Task} */
        openNewTask(task) {
            this.$emit('newTask', task, this.taskList);
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
    },
}
</script>