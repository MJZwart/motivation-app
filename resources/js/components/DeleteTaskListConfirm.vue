<template>
    <div>
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal" v-if="taskListToDelete">
                    <information-block></information-block>
                    <div class="form-title">
                    <h3>Delete task list</h3>
                    </div>
                    <div>
                        <form @submit.prevent="deleteTaskList">
                            <p class="modal-text">Are you sure you want to delete {{taskListToDelete.name}}</p>
                            <div v-if="taskListHasTasks">
                                <p class="modal-text">
                                    This task list has {{taskListTasks.length}} tasks. Do you wish to delete these or merge the tasks into a different task list?
                                </p>
                            
                                <div class="form-group">
                                    <select 
                                        id="deleteOption" 
                                        v-model="deleteOption">
                                        <option value="delete" selected>Delete</option>
                                        <option v-for="option in taskLists" :value="option.id" :key="option.key">Merge with: {{option.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="long-button">Delete task list</button>
                                <button type="button" class="long-button" @click="close">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>


<script>
import InformationBlock from './InformationBlock.vue';
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
            var taskLists = this.$store.getters['taskList/getTaskLists'];
            return taskLists.filter(item => item != this.taskListToDelete);
        },
    },
    methods: {
        deleteTaskList(){
            if(this.deleteOption != "delete"){
                const data = { taskListId : this.deleteOption, tasks: this.taskListTasks};
                this.$store.dispatch('taskList/mergeTasks', data);
            }
            var self = this;
            this.$store.dispatch('taskList/deleteTaskList', this.taskListToDelete).then(function(){
                self.close();
            });
        },
        close(){
            this.taskListToDelete = {},
            this.$emit('close');
        }
    },
}
</script>
