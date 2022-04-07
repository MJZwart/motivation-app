<template>
    <div>
        <Summary :footer="true" class="p-0">
            <template #header>
                <span class="d-flex">
                    {{taskList.name}}
                    <span class="ml-auto">
                        <Tooltip :text="$t('edit-task-list')">
                            <FaIcon 
                                icon="fa-regular fa-pen-to-square"
                                class="icon white small"
                                @click="editTaskList()" />
                        </Tooltip>
                        <Tooltip :text="$t('delete-task-list')">
                            <FaIcon 
                                icon="fa-solid fa-trash"
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
                            icon="fa-solid fa-square-plus"
                            class="icon large green m-0 wide" />
                    </Tooltip>
                </button>
            </template>
        </Summary>
    </div>
</template>


<script>
import Tooltip from '../bootstrap/Tooltip.vue';
import Task from './Task.vue';
import Summary from '../summary/Summary.vue';
export default {
    components: {Task, Summary, Tooltip},
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