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


<script setup>
import {ref} from 'vue';
import BaseFormError from '../BaseFormError.vue';
import {useTaskStore} from '@/store/taskStore';
const taskStore = useTaskStore();
const emit = defineEmits(['close']);

/** @type {import('resources/types/task').TaskList} */
const taskList = ref({});

async function submitTaskList() {
    await taskStore.storeTaskList(taskList.value);
    close();
}
function close() {
    taskList.value = {};
    emit('close');
}
</script>
