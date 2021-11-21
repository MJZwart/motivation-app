<template>
    <div>
        <information-block></information-block>
        <b-form @submit.prevent="submitTaskList">
            <b-form-group
                label="Task list name" 
                label-for="name">
                <b-form-input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Name" 
                    required
                    v-model="taskList.name" />
            </b-form-group>
            <b-button type="submit" block>Create new task list</b-button>
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
    data() {
        return {
            taskList: {},
        }
    },
    methods: {
        submitTaskList(){
            var self = this;
            this.$store.dispatch('taskList/storeTaskList', this.taskList).then(function(){
                self.close();
            });

        },
        close(){
            this.taskList = {},
            this.$emit('close');
        }
    },
}
</script>
