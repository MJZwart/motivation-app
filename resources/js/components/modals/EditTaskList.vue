<template>
    <div v-if="editedTaskList">
        <b-form @submit.prevent="updateTaskList">
            <b-form-group
                :label="$t('task-list-name')" 
                label-for="name">
                <b-form-input 
                    type="text" 
                    id="name" 
                    name="name" 
                    :placeholder="$t('name')" 
                    v-model="editedTaskList.name" />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('update-task-list') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
            <base-form-error name="error" /> 
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';

export default {
    components: {
        BaseFormError,
    },
    props: {
        taskList: Object,
    },
    data() {
        return {
            editedTaskList: {},
        }
    },
    mounted(){
        this.taskList ? this.editedTaskList = Vue.util.extend({}, this.taskList) : this.editedTaskList = {};
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
