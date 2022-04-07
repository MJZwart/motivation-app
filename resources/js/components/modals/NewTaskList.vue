<template>
    <div>
        <form @submit.prevent="submitTaskList">
            <div class="form-group">
                <label for="name">{{$t('task-list-name')}}</label>
                <input 
                    id="name" 
                    v-model="taskList.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </div>
            <button type="submit" class="block">Create new task list</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
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
