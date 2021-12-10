<template>
    <div v-if="taskListToDelete">
        <b-form @submit.prevent="deleteTaskList">
            <p>{{ $t('are-you-sure-delete') }} {{taskListToDelete.name}}</p>
            <b-form-group v-if="taskListHasTasks">
                <p>
                    {{ $t('task-list-has-tasks', [taskListTasks.length]) }} 
                </p>
            
                <b-form-group>
                    <b-form-select 
                        id="deleteOption" 
                        v-model="deleteOption">
                        <option value="delete" selected>{{ $t('delete') }}</option>
                        <option v-for="option in taskLists" :key="option.key" :value="option.id">
                            {{ $t('merge-with') }} {{option.name}}
                        </option>
                    </b-form-select>
                </b-form-group>
            </b-form-group>
            <b-button type="submit" block>{{ $t('delete-task-list-confirm') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
            <base-form-error name="error" /> 
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';

export default {
    components: {
        BaseFormError,
    },
    props: {
        taskList: Object,
    },
    mounted() {
        this.taskListToDelete = this.taskList;
    },
    data() {
        return {
            taskListToDelete: null,
            deleteOption: 'delete',
        }
    },
    computed: {
        taskListHasTasks() {
            return !!this.taskListToDelete && !!this.taskListToDelete.tasks[0];
        },
        taskListTasks() {
            return this.taskListToDelete.tasks;
        },
        taskLists() {
            let taskLists = this.$store.getters['taskList/getTaskLists'];
            return taskLists.filter(item => item != this.taskListToDelete);
        },
    },
    methods: {
        deleteTaskList() {
            if (this.deleteOption != 'delete') {
                const data = {taskListId : this.deleteOption, tasks: this.taskListTasks};
                this.$store.dispatch('taskList/mergeTasks', data);
            }
            this.$store.dispatch('taskList/deleteTaskList', this.taskListToDelete).then(() => {
                this.close();
            });
        },
        close() {
            this.taskListToDelete = null;
            this.$emit('close');
        },
    },
}
</script>
