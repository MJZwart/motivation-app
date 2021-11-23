<template>
    <div>
        <information-block></information-block>
        <b-form @submit.prevent="updateTaskList">
            <b-form-group
                label="Task list name" 
                label-for="name">
                <b-form-input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Name" 
                    v-model="editedTaskList.name" />
            </b-form-group>
            <b-button type="submit" block>Update task list</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
        </b-form>
    </div>
</template>


<script>
import InformationBlock from '../InformationBlock.vue';
export default {
    components: {InformationBlock},
    props: {
        taskList: Object,
    },
    data() {
        return {
            editedTaskList: {},
        }
    },
    mounted(){
        this.taskList ? this.editedTaskList = this.taskList : this.editedTaskList = {};
    },
    methods: {
        updateTaskList(){
            var self = this;
            this.$store.dispatch('taskList/updateTaskList', this.editedTaskList).then(function(){
                self.close();
            });

        },
        close(){
            this.editedTaskList = {},
            this.$emit('close');
        }
    },
}
</script>
