<template>
    <div class="dummy-tasks">
        <Summary :footer="true" class="p-0">
            <template #header>
                <span class="d-flex">
                    {{taskList.name}}
                    <span class="ml-auto">
                        <Tooltip :text="$t('edit-task-list')">
                            <Icon 
                                :icon="EDIT"
                                class="edit-icon small white" />
                        </Tooltip>
                        <Tooltip :text="$t('delete-task-list')">
                            <Icon 
                                :icon="TRASH"
                                class="delete-icon small white" />
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
                <button class="block bottom-radius p-0 new-task" variant="outline">
                    {{$t('new-task')}}
                </button>
            </template>
        </Summary>
    </div>
</template>


<script setup>
import Task from './DummyTask.vue';
import Summary from '/js/components/global/Summary.vue';
import {EDIT, TRASH} from '/js/constants/iconConstants';

const props = defineProps({
    taskList: {
        type: Object,
        required: true,
    },
});

function taskClass(/** @type {number} */ index) {
    return index == props.taskList.tasks.length -1 ? 'task task-last' : 'task';
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
    svg, button {
        cursor: default;
    }
}
</style>