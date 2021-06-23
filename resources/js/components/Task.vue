<template>
    <div>
        <p class="task-title">{{task.name}}</p>
        <p class="task-description">{{task.description}}</p>
        <div class="sub-task border" v-for="subTask in task.tasks" :key="subTask.id">
            <p class="task-title">{{subTask.name}}</p>
            <p class="task-description">{{subTask.description}}</p>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        task: Object,
    },
}
</script>
