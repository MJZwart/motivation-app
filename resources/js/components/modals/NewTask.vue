<template>
    <div>
        <information-block></information-block>
        <b-form @submit.prevent="submitTask">
            <b-form-group 
                label="Task name" 
                label-for="name">
                <b-form-input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Name" 
                    required
                    v-model="task.name" />
            </b-form-group >
            <b-form-group
                label="Description (optional)" 
                label-for="description">
                <b-form-input  
                    type="text" 
                    id="description" 
                    name="description" 
                    placeholder="Description" 
                    v-model="task.description" />
            </b-form-group>
            <b-form-group
                label="Type" 
                label-for="type">
                <b-form-select
                    name="type"
                    id="type"
                    v-model="task.type"
                    :options="taskTypes">
                </b-form-select>
            </b-form-group>
            <b-form-group
                :label="'Difficulty: '+task.difficulty+'/5'"
                label-for="difficulty">
                <b-form-input 
                    type="range"
                    name="difficulty"
                    id="difficulty"
                    min="1"
                    max="5"
                    value="3"
                    v-model="task.difficulty" />
            </b-form-group>
            <b-form-group
                label="Repeatable" 
                label-for="repeatable">
                <b-form-select
                    name="repeatable"
                    id="repeatable"
                    v-model="task.repeatable"
                    :options="repeatables">
                </b-form-select>
            </b-form-group>
            <b-form-group>
                <p v-if="taskList">Task list: {{taskList.name}}</p>
                <p v-if="superTask">Subtask of: {{superTask.name}}</p>
            </b-form-group>
            <b-button type="submit" block>Create new task</b-button>
            <b-button type="button" block @click="close">Cancel</b-button>
        </b-form>
    </div>
</template>


<script>
import InformationBlock from '../InformationBlock.vue';
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
            //TODO separate this
            taskTypes: [{text: 'Generic', value: 1}, 
                {text: 'Physical', value: 2}, 
                {text: 'Mental', value: 3}, 
                {text: 'GenSocialeric', value: 4}],
            repeatables: [{text: 'Not repeatable', value:"NONE"},
                {text: 'Daily', value:"DAILY"},
                {text: 'Weekly', value:"WEEKLY"},
                {text: 'Monthly', value:"MONTHLY"},
                {text: 'Infinite', value:"INFINITE"}],
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
