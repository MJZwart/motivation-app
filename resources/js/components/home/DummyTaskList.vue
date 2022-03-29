<template>
    <div>
        <Summary :footer="true" class="p-0">
            <template #header>
                <span class="d-flex">
                    {{taskList.name}}
                    <span class="ml-auto">
                        <b-icon-pencil-square 
                            :id="'edit-task-list-' + taskList.id"
                            class="icon white small" />
                        <b-tooltip :target="'edit-task-list-' + taskList.id">{{ $t('edit-task-list') }}</b-tooltip>
                        <b-icon-trash 
                            :id="'delete-task-list-' + taskList.id"
                            class="icon white small" />
                        <b-tooltip :target="'delete-task-list-' + taskList.id">{{ $t('delete-task-list') }}</b-tooltip>
                    </span>
                </span>
            </template>
            <slot>
                <template v-for="(task, index) in taskList.tasks" :key="task.id" >
                    <Task 
                        :task="task" 
                        :class="taskClass(index)" />
                </template>
            </slot>
            <template #footer>           
                <b-button block variant="outline" class="bottom-radius p-0">
                    <b-icon-plus-square-fill 
                        :id="'add-new-task-' + taskList.id" 
                        class="icon large green m-0 wide" 
                    />
                    <b-tooltip :target="'add-new-task-' + taskList.id">{{ $t('add-new-task') }}</b-tooltip>
                </b-button>
            </template>
        </Summary>
    </div>
</template>


<script>
import Task from './DummyTask.vue';
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