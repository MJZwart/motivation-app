<template>
    <div>
        <span class="card-title d-flex">{{taskList.name}}
            <span class="ml-auto">
                <b-icon-pencil-square 
                    class="icon-small"
                    @click="editTaskList()"></b-icon-pencil-square>
                <b-icon-trash 
                    class="icon-small"
                    @click="deleteTaskList()"></b-icon-trash>
            </span>

        </span>
        <template v-for="task in taskList.tasks">

            <task 
                :task="task" 
                :key="task.id" 
                class="task side-border" 
                v-on:newTask="openNewTask"
                v-on:editTask="editTask"></task>

        </template>
        <div>
            <b-button block class="bottom-radius" @click="openNewTask(null)">Add new task</b-button>
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
            this.$emit('deleteTaskList', this.taskList);
        },
    }
}
</script>