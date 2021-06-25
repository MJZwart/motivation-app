<template>
    <div>
        <h3>{{taskList.name}}</h3>
        <template v-for="task in taskList.tasks">
            <task :task="task" :key="task.id" class="task border" v-on:newTask="openNewTask"></task>
        </template>
        <div>
            <button class="long-button" @click="openNewTask(null)">Add new task</button>
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
        }
    }
}
</script>
