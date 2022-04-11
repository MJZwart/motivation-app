<template>
    <div v-if="editedTaskList">
        <form @submit.prevent="updateTaskList">
            <div class="form-group">
                <label for="name">{{$t('task-list-name')}}</label>
                <input 
                    id="name" 
                    v-model="editedTaskList.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </div>
            <button type="submit" class="block">{{ $t('update-task-list') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
            <base-form-error name="error" /> 
        </form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import {shallowRef} from 'vue';

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
        this.taskList ? this.editedTaskList = shallowRef(this.taskList) : this.editedTaskList = {};
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
