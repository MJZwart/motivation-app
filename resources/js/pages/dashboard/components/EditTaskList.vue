<template>
    <div v-if="editedTaskList">
        <form @submit.prevent="updateTaskList">
            <SimpleInput 
                id="name" 
                v-model="editedTaskList.name"
                type="text" 
                name="name" 
                :label="$t('task-list-name')"
                :placeholder="$t('name')"  />
            <SubmitButton class="block">{{ $t('update-task-list') }}</SubmitButton>
            <button type="button" class="block button-cancel" @click="close">{{ $t('cancel') }}</button>
            <BaseFormError name="error" /> 
        </form>
    </div>
</template>


<script setup lang="ts">
import type {TaskList} from 'resources/types/task';
import {ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
const taskStore = useTaskStore();

const props = defineProps<{taskList: TaskList}>();
const emit = defineEmits(['close']);


const editedTaskList = ref<TaskList>(Object.assign({}, props.taskList));

async function updateTaskList() {
    await taskStore.updateTaskList(editedTaskList.value)
    close();
}
function close() {
    emit('close');
}
</script>
