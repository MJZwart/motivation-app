<template>
    <div>
        <b-form @submit.prevent="submitTaskList">
            <b-form-group
                :label="$t('task-list-name')" 
                label-for="name">
                <b-form-input 
                    id="name" 
                    v-model="taskList.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-button type="submit" block>Create new task list</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
export default {
    components: {
        BaseFormError,
    },
    data() {
        return {
            /** @type {import('resources/types/task').TaskList} */
            taskList: {},
        }
    },
    methods: {
        submitTaskList() {
            var self = this;
            this.$store.dispatch('taskList/storeTaskList', this.taskList).then(function() {
                self.close();
            });

        },
        close() {
            this.taskList = {},
            this.$emit('close');
        },
    },
}
</script>
