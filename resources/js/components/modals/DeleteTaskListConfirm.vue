<template>
    <div>
        <information-block></information-block>
        <b-form @submit.prevent="deleteTaskList">
            <p class="modal-text">Are you sure you want to delete {{taskListToDelete.name}}</p>
            <b-form-group v-if="taskListHasTasks">
                <p class="modal-text">
                    This task list has {{taskListTasks.length}} tasks. Do you wish to delete these or merge the tasks into a different task list?
                </p>
            
                <b-form-group>
                    <select 
                        id="deleteOption" 
                        v-model="deleteOption">
                        <option value="delete" selected>Delete</option>
                        <option v-for="option in taskLists" :value="option.id" :key="option.key">Merge with: {{option.name}}</option>
                    </select>
                </b-form-group>
            </b-form-group>
            <b-button type="submit" block>Delete task list</b-button>
            <b-button type="button" block @click="close">Cancel</b-button>
        </b-form>
    </div>
</template>


<script>
import InformationBlock from '../InformationBlock.vue';
export default {
    components: {
        InformationBlock,
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
            deleteOption: "delete",
        }
    },
    computed: {
        taskListHasTasks(){
            if(this.taskListToDelete){
                return !!this.taskListToDelete.tasks[0];
            }
        },
        taskListTasks(){
            return this.taskListToDelete.tasks;
        },
        taskLists(){
            let taskLists = this.$store.getters['taskList/getTaskLists'];
            return taskLists.filter(item => item != this.taskListToDelete);
        },
    },
    methods: {
        deleteTaskList(){
            if(this.deleteOption != "delete"){
                const data = { taskListId : this.deleteOption, tasks: this.taskListTasks};
                this.$store.dispatch('taskList/mergeTasks', data);
            }
            this.$store.dispatch('taskList/deleteTaskList', this.taskListToDelete).then(response =>{
                this.close();
            });
        },
        close(){
            this.taskListToDelete = {},
            this.$emit('close');
        }
    },
}
</script>
