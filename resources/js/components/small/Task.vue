<template>
    <div>
        <p class="task-title d-flex">
            <b-icon-check-square
                class="icon small green"
                @click="completeTask(task)" />
            {{task.name}}             
            <span class="ml-auto">
                <b-icon-plus-square-fill
                    class="icon small green"
                    @click="openNewTask(task)" />
                <b-icon-pencil-square 
                    class="icon small"
                    @click="editTask(task)" />
                <b-icon-trash 
                    class="icon small red"
                    @click="deleteTask(task)" />
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>

        <div v-for="subTask in task.tasks" :key="subTask.id" class="sub-task">
            <p class="task-title d-flex">
                <b-icon-arrow-return-right />
                <b-icon-check-square
                    class="icon small green"
                    @click="completeTask(subTask)" />
                {{subTask.name}}
                <b-icon-pencil-square 
                    class="icon small ml-auto"
                    @click="editTask(subTask)" />
                <b-icon-trash
                    class="icon small red"
                    @click="deleteTask(subTask)" />
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