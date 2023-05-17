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
                                class="edit-icon small" />
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
                <Task 
                    :task="task" 
                    :class="taskClass(index)" />
            </template>
            <button class="block bottom-radius p-0 new-task" variant="outline">
                {{$t('new-task')}}
            </button>
        </ContentBlock>
    </div>
</template>


<script setup lang="ts">
import Task from './DummyTask.vue';
import {EDIT, TRASH} from '/js/constants/iconConstants';
import {DummyTaskList} from './homepageService';

const props = defineProps<{taskList: DummyTaskList}>();

function taskClass(index: number) {
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