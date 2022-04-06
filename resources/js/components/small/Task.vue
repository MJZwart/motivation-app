<template>
    <div>
        <p class="task-title d-flex">
            <Tooltip :text="$t('complete-task')">
                <FaIcon icon="fa-pen-to-square" />
                <b-icon-check-square
                    :id="'complete-task-' + task.id"
                    class="icon small green"
                    @click="completeTask(task)" />
            </Tooltip>
            {{task.name}}             
            <span class="ml-auto">
                <Tooltip :text="$t('new-sub-task')">
                    <b-icon-plus-square-fill
                        :id="'new-sub-task-' + task.id"
                        class="icon small green"
                        @click="openNewTask(task)" />
                </Tooltip>
                <Tooltip :text="$t('edit-task')">
                    <b-icon-pencil-square 
                        :id="'edit-task-' + task.id"
                        class="icon small"
                        @click="editTask(task)" />
                </Tooltip>
                <Tooltip :text="$t('delete-task')">
                    <b-icon-trash 
                        :id="'delete-task-' + task.id"
                        class="icon small red"
                        @click="deleteTask(task)" />
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
                        class="icon small green"
                        @click="completeTask(subTask)" />
                </Tooltip>
                {{subTask.name}}
                <Tooltip :text="$t('edit-sub-task')">
                    <b-icon-pencil-square 
                        :id="'edit-sub-task-' + subTask.id"
                        class="icon small ml-auto"
                        @click="editTask(subTask)" />
                </Tooltip>
                <Tooltip :text="$t('delete-sub-task')">
                    <b-icon-trash
                        :id="'delete-sub-task-' + subTask.id"
                        class="icon small red"
                        @click="deleteTask(subTask)" />
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