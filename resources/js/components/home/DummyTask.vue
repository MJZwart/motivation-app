<template>
    <div>
        <p class="task-title d-flex">
            <Tooltip :text="$t('complete-task')">
                <b-icon-check-square
                    :id="'complete-task-' + task.id"
                    class="icon small green" />
            </Tooltip>
            {{task.name}}             
            <span class="ml-auto">
                <Tooltip :text="$t('new-sub-task')">
                    <b-icon-plus-square-fill
                        :id="'new-sub-task-' + task.id"
                        class="icon small green" />
                </Tooltip>
                <Tooltip :text="$t('edit-task')">
                    <b-icon-pencil-square 
                        :id="'edit-task-' + task.id"
                        class="icon small" />
                </Tooltip>
                <Tooltip :text="$t('delete-task')">
                    <b-icon-trash 
                        :id="'delete-task-' + task.id"
                        class="icon small red" />
                </Tooltip>
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>

        <div v-for="subTask in task.tasks" :key="subTask.id" class="sub-task">
            <p class="task-title d-flex">
                <b-icon-arrow-return-right />
                <Tooltip :text="$t('complete-sub-task')">
                    <b-icon-check-square
                        :id="'complete-sub-task-' + subTask.id"
                        class="icon small green" />
                </Tooltip>
                {{subTask.name}}
                <Tooltip :text="$t('edit-sub-task')">
                    <b-icon-pencil-square 
                        :id="'edit-sub-task-' + subTask.id"
                        class="icon small ml-auto" />
                </Tooltip>
                <Tooltip :text="$t('delete-sub-task')">
                    <b-icon-trash
                        :id="'delete-sub-task-' + subTask.id"
                        class="icon small red" />
                </Tooltip>
            </p>
            <p class="task-description">{{subTask.description}}</p>
        </div>
    </div>
</template>


<script>
import Tooltip from '../bootstrap/Tooltip.vue';
export default {
    components: {Tooltip},
    props: {
        task: {
            /** @type {import('resources/types/task').Task} */
            type: Object,
            required: true,
        },
    },
}
</script>