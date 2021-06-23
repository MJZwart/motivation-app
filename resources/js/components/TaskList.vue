<template>
    <div>
        <h3>{{taskList.name}}</h3>
        <template v-for="task in taskList.tasks">
            <task :task="task" :key="task.id" class="task border"></task>
        </template>
        <div>
            <p>Add new task</p>
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
    font-size:14px;
    font-weight:700;
}
</style>