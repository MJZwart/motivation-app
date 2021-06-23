<template>
    <div>
        <h2>Character</h2>
        <new-task :preset="preset"></new-task>
    </div>
</template>


<script>
import NewTask from '../components/NewTask.vue'
export default {
    components: {NewTask},
    data() {
        return {
            preset: {
                super_task_id: null,
                task_list_id: 71,
            },
        }
    },
    
}
</script>



<style>

</style>