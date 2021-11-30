<template>
    <div>
        <p class="task-title d-flex">
            <b-icon-check-square
                class="icon-small green"
                @click="completeTask(task)"></b-icon-check-square>
            {{task.name}}             
            <span class="ml-auto">
                <b-icon-plus-square-fill
                    class="icon-small green"
                    @click="openNewTask(task)"></b-icon-plus-square-fill>
                <b-icon-pencil-square 
                    class="icon-small"
                    @click="editTask(task)"></b-icon-pencil-square>
                <b-icon-trash 
                    class="icon-small red"
                    @click="deleteTask(task)"></b-icon-trash>
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>

        <div class="sub-task" v-for="subTask in task.tasks" :key="subTask.id">
            <p class="task-title d-flex">
                <b-icon-arrow-return-right></b-icon-arrow-return-right>
                <b-icon-check-square
                    class="icon-small green"
                    @click="completeTask(subTask)"></b-icon-check-square>
                {{subTask.name}}
                <b-icon-pencil-square 
                    class="icon-small ml-auto"
                    @click="editTask(subTask)"></b-icon-pencil-square>
                <b-icon-trash
                    class="icon-small red"
                    @click="deleteTask(subTask)"></b-icon-trash>
            </p>
            <p class="task-description">{{subTask.description}}</p>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        task: Object,
    },
    methods: {
        openNewTask(task){
            this.$emit('newTask', task);
        },
        editTask(task){
            this.$emit('editTask', task);
        },
        deleteTask(task){
            if(confirm('Are you sure you wish to delete the task \'' + task.name + '\' without completing it? Any subtasks will automatically be deleted. You will not receive any rewards for it.')){
                this.$store.dispatch('task/deleteTask', task);
            }
        },
        completeTask(task){
            if(task.tasks.length > 0){
                if(confirm('Completing this task also completes all sub tasks. Are you sure?')){
                    this.$store.dispatch('task/completeTask', task);
                }
            } else {
                this.$store.dispatch('task/completeTask', task);
            }
        },
    }
}
</script>