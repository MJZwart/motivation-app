<template>
    <div>
        <p class="task-title d-flex">
            <b-icon-check-square
                :id="'complete-task-' + task.id"
                class="icon small green" />
            <b-tooltip :target="'complete-task-' + task.id">{{ $t('complete-task') }}</b-tooltip>
            {{task.name}}             
            <span class="ml-auto">
                <b-icon-plus-square-fill
                    :id="'new-sub-task-' + task.id"
                    class="icon small green" />
                <b-tooltip :target="'new-sub-task-' + task.id">{{ $t('new-sub-task') }}</b-tooltip>
                <b-icon-pencil-square 
                    :id="'edit-task-' + task.id"
                    class="icon small" />
                <b-tooltip :target="'edit-task-' + task.id">{{ $t('edit-task') }}</b-tooltip>
                <b-icon-trash 
                    :id="'delete-task-' + task.id"
                    class="icon small red" />
                <b-tooltip :target="'delete-task-' + task.id">{{ $t('delete-task') }}</b-tooltip>
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>

        <div v-for="subTask in task.tasks" :key="subTask.id" class="sub-task">
            <p class="task-title d-flex">
                <b-icon-arrow-return-right />
                <b-icon-check-square
                    :id="'complete-sub-task-' + subTask.id"
                    class="icon small green" />
                <b-tooltip :target="'complete-sub-task-' + subTask.id">{{ $t('complete-sub-task') }}</b-tooltip>
                {{subTask.name}}
                <b-icon-pencil-square 
                    :id="'edit-sub-task-' + subTask.id"
                    class="icon small ml-auto" />
                <b-tooltip :target="'edit-sub-task-' + subTask.id">{{ $t('edit-sub-task') }}</b-tooltip>
                <b-icon-trash
                    :id="'delete-sub-task-' + subTask.id"
                    class="icon small red" />
                <b-tooltip :target="'delete-sub-task-' + subTask.id">{{ $t('delete-sub-task') }}</b-tooltip>
            </p>
            <p class="task-description">{{subTask.description}}</p>
        </div>
    </div>
</template>


<script>
export default {
    components: {},
    props: {
        task: {
            /** @type {import('resources/types/task').Task} */
            type: Object,
            required: true,
        },
    },
}
</script>