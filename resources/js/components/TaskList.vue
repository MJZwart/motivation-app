<template>
    <div>
        <h3>{{taskList.name}}</h3>
        <ul>
            <li v-for="task in taskList.tasks" :key="task.id">{{task.name}}</li>
        </ul>
    </div>
</template>


<script>
export default {
    props: {
        taskList: Object,
    },
}
</script>


<style>

</style>