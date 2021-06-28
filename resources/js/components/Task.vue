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
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>
        <div class="sub-task border" v-for="subTask in task.tasks" :key="subTask.id">
            <p class="task-title">{{subTask.name}}
                <button 
                    type="button"
                    class="button-small flex-end"
                    @click="editTask(subTask)">Edit</button>
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
    }
}
</script>

<style>
.task, .sub-task{
    margin:1px;
}
.sub-task{
    margin-left: 5px !important;
}
.task-description{
    font-size:12px;
}
.task-title{
    display:flex;
    font-size:14px;
    font-weight:700;
    margin-bottom:0;
}
</style>