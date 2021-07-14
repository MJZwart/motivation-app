<template>
    <div>
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal">
                    <information-block></information-block>
                    <div class="form-title">
                    <h3>New task</h3>
                    </div>
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
                            <p v-if="taskList">Task list: {{taskList.name}}</p>
                            <p v-if="superTask">Subtask of: {{superTask.name}}</p>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="long-button">Create new task</button>
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
    components: {
        InformationBlock,
    },
    props: {
        taskList: Object,
        superTask: Object,
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
            this.task.super_task_id = this.superTask ? this.superTask.id : null;
            this.task.task_list_id = this.taskList.id || null;
            var self = this;
            this.$store.dispatch('task/storeTask', this.task).then(function(){
                self.close();
            });
        },
        close(){
            this.task = {
                difficulty: 3,
                type: 1,
                repeatable: 'NONE',
            },
            this.$emit('close');
        }
    },
}
</script>
