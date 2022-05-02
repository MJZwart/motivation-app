<template>
    <div>
        <form @submit.prevent="submitTaskList">
            <Input 
                id="name" 
                v-model="taskList.name"
                type="text" 
                name="name" 
                :label="$t('task-list-name')"
                :placeholder="$t('name')"  />
            <button type="submit" class="block">Create new task list</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>


<script setup>
import {ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
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
