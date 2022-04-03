<template>
    <div v-if="editedTaskList">
        <b-form @submit.prevent="updateTaskList">
            <div class="form-group">
                <label for="name">{{$t('task-list-name')}}</label>
                <input 
                    id="name" 
                    v-model="editedTaskList.name"
                    class="form-control"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </div>
            <b-button type="submit" block>{{ $t('update-task-list') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
            <base-form-error name="error" /> 
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import Vue from 'vue';

export default {
    components: {
        BaseFormError,
    },
    props: {
        taskList: {
            /** @type {import('../../../types/task').TaskList} */
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            /** @type {import('../../../types/task').TaskList} */
            editedTaskList: {},
        }
    },
    mounted() {
        this.taskList ? this.editedTaskList = Vue.util.extend({}, this.taskList) : this.editedTaskList = {};
    },
    methods: {
        updateTaskList() {
            var self = this;
            this.$store.dispatch('taskList/updateTaskList', this.editedTaskList).then(function() {
                self.close();
            });

        },
        close() {
            this.editedTaskList = {},
            this.$emit('close');
        },
    },
}
</script>
