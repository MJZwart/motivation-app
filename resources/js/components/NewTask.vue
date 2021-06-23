<template>
    <div>
        <form @submit.prevent="submitTask">
            <div class="form-group">
                <label for="name">Task name</label>
                <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        placeholder="Name" 
                        v-model="task.name" />
            </div>
            <div class="form-group">
                <label for="description">Description (optional)</label>
                <input 
                    type="text" 
                    id="description" 
                    name="description" 
                    placeholder="Description" 
                    v-model="task.description" />
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select
                    name="type"
                    id="type"
                    v-model="task.type">
                    <option value="1">Generic</option>
                    <option value="2">Physical</option>
                    <option value="3">Mental</option>
                    <option value="4">Social</option>
                </select>
            </div>
            <div class="form-group">
                <label for="difficulty">Difficulty: {{task.difficulty}}/5</label>
                <input 
                    type="range"
                    name="difficulty"
                    id="difficulty"
                    min="1"
                    max="5"
                    value="3"
                    v-model="task.difficulty" />
            </div>
            <div class="form-group">
                <label for="repeatable">Repeatable</label>
                <select
                    name="repeatable"
                    id="repeatable"
                    v-model="task.repeatable">
                    <option value="NONE">Not repeatable</option>
                    <option value="DAILY">Daily</option>
                    <option value="WEEKLY">Weekly</option>
                    <option value="MONTHLY">Monthly</option>
                </select>
            </div>
            <div class="form-group">
                <label for=""></label>

            </div>
            <div class="form-group">
                <button type="submit" class="long-button">Create new task</button>
            </div>
        </form>
    </div>
</template>


<script>
export default {
    props: {
        preset: Object
    },
    data() {
        return {
            task: {
                difficulty: 3,
                type: 1,
                repeatable: 'NONE',
            },
        }
    },
    methods: {
        submitTask(){
            this.task.super_task_id = this.preset.super_task_id;
            this.task.task_list_id = this.preset.task_list_id;
            this.$store.dispatch('storeTask', this.task);
        },
    },
}
</script>


<style>

</style>