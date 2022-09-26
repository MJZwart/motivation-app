<template>
    <div>
        <form @submit.prevent="submitTaskList">
            <SimpleInput 
                id="name" 
                v-model="taskList.name"
                type="text" 
                name="name" 
                :label="$t('task-list-name')"
                :placeholder="$t('name')"  />
            <button id="create-new-task-button" type="submit" class="block">Create new task list</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>


<script setup lang="ts">
import {ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import type {NewTaskList} from 'resources/types/task';
const taskStore = useTaskStore();
const emit = defineEmits(['close']);

const taskList = ref<NewTaskList>(emptyTaskListInstance());

async function submitTaskList() {
    await taskStore.storeTaskList(taskList.value);
    close();
}
function close() {
    taskList.value = emptyTaskListInstance();
    emit('close');
}
function emptyTaskListInstance() {
    return {
        name: '',
    }
}
</script>
