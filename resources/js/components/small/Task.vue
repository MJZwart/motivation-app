<template>
    <div>
        <p class="task-title d-flex">
            <Tooltip :text="$t('complete-task')">
                <FaIcon 
                    :icon="['far', 'square-check']"
                    class="icon small green"
                    @click="completeTask(task)" />
            </Tooltip>
            {{task.name}}             
            <span class="ml-auto">
                <Tooltip :text="$t('new-sub-task')">
                    <FaIcon 
                        icon="square-plus"
                        class="icon small green"
                        @click="openNewTask(task)" />
                </Tooltip>
                <Tooltip :text="$t('edit-task')">
                    <FaIcon 
                        :icon="['far', 'pen-to-square']"
                        class="icon small"
                        @click="editTask(task)" />
                </Tooltip>
                <Tooltip :text="$t('delete-task')">
                    <FaIcon 
                        icon="trash"
                        class="icon small red"
                        @click="deleteTask(task)" />
                </Tooltip>
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>

        <div v-for="subTask in task.tasks" :key="subTask.id" class="sub-task">
            <p class="task-title d-flex">
                <FaIcon icon="arrow-turn-up" rotation="90" />
                <Tooltip :text="$t('complete-sub-task')">
                    <FaIcon 
                        :icon="['far', 'square-check']"
                        class="icon small green"
                        @click="completeTask(subTask)" />
                </Tooltip>
                {{subTask.name}}
                <Tooltip :text="$t('edit-sub-task')" class="ml-auto">
                    <FaIcon 
                        :icon="['far', 'pen-to-square']"
                        class="icon small"
                        @click="editTask(subTask)" />
                </Tooltip>
                <Tooltip :text="$t('delete-sub-task')">
                    <FaIcon 
                        icon="trash"
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