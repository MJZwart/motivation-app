<template>
    <div>
        <Summary :footer="true" class="p-0">
            <template #header>
                <span class="d-flex">
                    {{taskList.name}}
                    <span class="ml-auto">
                        <Tooltip :text="$t('edit-task-list')">
                            <b-icon-pencil-square 
                                :id="'edit-task-list-' + taskList.id"
                                class="icon white small" />
                        </Tooltip>
                        <Tooltip :text="$t('delete-task-list')">
                            <b-icon-trash 
                                :id="'delete-task-list-' + taskList.id"
                                class="icon white small" />
                        </Tooltip>
                    </span>
                </span>
            </template>
            <slot>
                <template v-for="(task, index) in taskList.tasks">
                    <Task 
                        :key="task.id" 
                        :task="task" 
                        :class="taskClass(index)" />
                </template>
            </slot>
            <template #footer>           
                <button class="block clear bottom-radius p-0" variant="outline">
                    <Tooltip :text="$t('add-new-task')">
                        <b-icon-plus-square-fill 
                            :id="'add-new-task-' + taskList.id" 
                            class="icon large green m-0 wide" 
                        />
                    </Tooltip>
                </button>
            </template>
        </Summary>
    </div>
</template>


<script>
import Tooltip from '../bootstrap/Tooltip.vue';
import Task from './DummyTask.vue';
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