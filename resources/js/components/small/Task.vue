<template>
    <div>
        <p class="task-title d-flex">
            <b-icon-check-square
                :id="'complete-task-' + task.id"
                class="icon small green"
                @click="completeTask(task)" />
            <b-tooltip :target="'complete-task-' + task.id">{{ $t('complete-task') }}</b-tooltip>
            {{task.name}}             
            <span class="ml-auto">
                <b-icon-plus-square-fill
                    :id="'new-sub-task-' + task.id"
                    class="icon small green"
                    @click="openNewTask(task)" />
                <b-tooltip :target="'new-sub-task-' + task.id">{{ $t('new-sub-task') }}</b-tooltip>
                <b-icon-pencil-square 
                    :id="'edit-task-' + task.id"
                    class="icon small"
                    @click="editTask(task)" />
                <b-tooltip :target="'edit-task-' + task.id">{{ $t('edit-task') }}</b-tooltip>
                <b-icon-trash 
                    :id="'delete-task-' + task.id"
                    class="icon small red"
                    @click="deleteTask(task)" />
                <b-tooltip :target="'delete-task-' + task.id">{{ $t('delete-task') }}</b-tooltip>
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>

        <div v-for="subTask in task.tasks" :key="subTask.id" class="sub-task">
            <p class="task-title d-flex">
                <b-icon-arrow-return-right />
                <b-icon-check-square
                    :id="'complete-sub-task-' + subTask.id"
                    class="icon small green"
                    @click="completeTask(subTask)" />
                <b-tooltip :target="'complete-sub-task-' + subTask.id">{{ $t('complete-sub-task') }}</b-tooltip>
                {{subTask.name}}
                <b-icon-pencil-square 
                    :id="'edit-sub-task-' + subTask.id"
                    class="icon small ml-auto"
                    @click="editTask(subTask)" />
                <b-tooltip :target="'edit-sub-task-' + subTask.id">{{ $t('edit-sub-task') }}</b-tooltip>
                <b-icon-trash
                    :id="'delete-sub-task-' + subTask.id"
                    class="icon small red"
                    @click="deleteTask(subTask)" />
                <b-tooltip :target="'delete-sub-task-' + subTask.id">{{ $t('delete-sub-task') }}</b-tooltip>
            </p>
            <p class="task-description">{{subTask.description}}</p>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        task: {
            /** @type {import('resources/types/task').Task} */
            type: Object,
            required: true,
        },
    },
    methods: {
        /** @param {import('resources/types/task').Task} task */
        openNewTask(task) {
            this.$emit('newTask', task);
        },
        /** @param {import('resources/types/task').Task} task */
        editTask(task) {
            this.$emit('editTask', task);
        },
        /** @param {import('resources/types/task').Task} task */
        deleteTask(task) {
            const confirmationText = this.$t('confirmation-delete-task', [task.name]);
            if (confirm(confirmationText)) {
                this.$store.dispatch('task/deleteTask', task);
            }
        },
        /** @param {import('resources/types/task').Task} task */
        completeTask(task) {
            if (task.tasks.length > 0) {
                if (confirm(this.$t('complete-sub-task-confirmation'))) {
                    this.$store.dispatch('task/completeTask', task);
                }
            } else {
                this.$store.dispatch('task/completeTask', task);
            }
        },
    },
}
</script>