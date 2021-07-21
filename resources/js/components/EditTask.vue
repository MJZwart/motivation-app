<template>
    <div>
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal">
                    <information-block></information-block>

                    <div class="form-title">
                    <h3>New task</h3>
                    </div>
                    <form @submit.prevent="updateTask">
                        <div class="form-group">
                            <label for="name">Task name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Name" 
                                v-model="editedTask.name" />
                        </div>
                        <div class="form-group">
                            <label for="description">Description (optional)</label>
                            <input 
                                type="text" 
                                id="description" 
                                name="description" 
                                placeholder="Description" 
                                v-model="editedTask.description" />
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select
                                name="type"
                                id="type"
                                v-model="editedTask.type">
                                <option value="1">Generic</option>
                                <option value="2">Physical</option>
                                <option value="3">Mental</option>
                                <option value="4">Social</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="difficulty">Difficulty: {{editedTask.difficulty}}/5</label>
                            <input 
                                type="range"
                                name="difficulty"
                                id="difficulty"
                                min="1"
                                max="5"
                                value="3"
                                v-model="editedTask.difficulty" />
                        </div>
                        <div class="form-group">
                            <label for="repeatable">Repeatable</label>
                            <select
                                name="repeatable"
                                id="repeatable"
                                v-model="editedTask.repeatable">
                                <option value="NONE">Not repeatable</option>
                                <option value="DAILY">Daily</option>
                                <option value="WEEKLY">Weekly</option>
                                <option value="MONTHLY">Monthly</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <p v-if="editedTask.taskList">Task list: {{editedTask.taskList}}</p>
                            <p v-if="editedTask.superTask">Subtask of: {{editedTask.superTask}}</p>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="long-button">Edit task</button>
                            <button type="button" class="long-button" @click="close">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </transition>
    </div>
</template>


<script>
import InformationBlock from './InformationBlock.vue';
export default {
    components: {InformationBlock},
    props: {
        task: Object,
    },
    data() {
        return {
            editedTask: {},
        }
    },
    mounted(){
        this.task ? this.editedTask = this.task : this.editedTask = {};
    },
    methods: {
        updateTask(){
            this.$store.dispatch('task/updateTask', this.editedTask).then(response => {
                this.close();
            });
        },
        close(){
            this.$emit('close');
        }
    },
}
</script>
