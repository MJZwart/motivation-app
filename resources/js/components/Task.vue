<template>
    <div>
        <p class="task-title flex">{{task.name}}     
                          
            <span class="flex-end">
                <button 
                    type="button"
                    class="button-small"
                    @click="openNewTask(task)">+</button>
                <button 
                    type="button"
                    class="button-small"
                    @click="editTask(task)">Edit</button>
                <button 
                    type="button"
                    class="button-small"
                    @click="deleteTask(task)">Delete</button>
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>

        <div class="sub-task" v-for="subTask in task.tasks" :key="subTask.id">
            <p class="task-title">- {{subTask.name}}
                <button 
                    type="button"
                    class="button-small flex-end"
                    @click="editTask(subTask)">Edit</button>
                <button 
                    type="button"
                    class="button-small"
                    @click="deleteTask(subTask)">Delete</button>
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
                this.$store.dispatch('deleteTask', task);
            }
        },
    }
}
</script>

<style>

</style>