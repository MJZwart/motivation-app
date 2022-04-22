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
                                class="icon white small" />
                        </Tooltip>
                        <Tooltip :text="$t('delete-task-list')">
                            <FaIcon 
                                icon="trash"
                                class="icon small white" />
                        </Tooltip>
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
                <button class="block clear bottom-radius p-0" variant="outline">
                    <Tooltip :text="$t('add-new-task')">
                        <FaIcon 
                            icon="square-plus"
                            class="icon large green m-0 wide" />
                    </Tooltip>
                </button>
            </template>
        </Summary>
    </div>
</template>


<script setup>

import Tooltip from '../bootstrap/Tooltip.vue';
import Task from './DummyTask.vue';
import Summary from '../summary/Summary.vue';

const props = defineProps({
    taskList: {
        type: Object,
        required: true,
    },
});

function taskClass(/** @type {number} */ index) {
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