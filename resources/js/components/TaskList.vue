<template>
    <div>
        <span class="frame-title flex">{{taskList.name}}
            <span class="flex-end">
                <button 
                    type="button"
                    class="button-small"
                    @click="editTaskList()">Edit</button>
                <button 
                    type="button"
                    class="button-small"
                    @click="deleteTaskList()">Delete</button>
            </span>

        </span>
        <template v-for="task in taskList.tasks">

            <task 
                :task="task" 
                :key="task.id" 
                class="task side-border no-margin" 
                v-on:newTask="openNewTask"
                v-on:editTask="editTask"></task>

        </template>
        <div>
            <button class="long-button no-margin bottom-radius" @click="openNewTask(null)">Add new task</button>
        </div>
    </div>
</template>


<script>
import Task from './Task.vue';
export default {
    components: {Task},
    props: {
        taskList: Object,
    },
    methods: {
        openNewTask(task){
            this.$emit('newTask', task, this.taskList);
        },
        editTask(task){
            this.$emit('editTask', task);
        },
        editTaskList(){
            this.$emit('editTaskList', this.taskList);
        },
        deleteTaskList(){
            if(confirm('Are you sure you want to delete ' + this.taskList.name + '?')){
                this.$store.dispatch('deleteTaskList', this.taskList);
            }
        },
    }
}
</script>


<style>
.frame-title{
    font-size:18px;
    font-weight: 600;
    border: 1px solid teal;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
    display: block;
    padding:5px;
}
.task{
    margin:0;
    padding:5px;
    border-bottom: 1px solid teal;
}
.sub-task{
    margin:-6px;
    padding:5px;
    padding-left:15px;
    border-bottom: 1px solid teal;
}
.task-description{
    font-size:12px;
    font-weight:400;
    color:#586069;
    margin:0px;
}
.task-title{
    display:flex;
    font-size:16px;
    font-weight:600;
    margin-top:0;
    margin-bottom:0;
}
</style>