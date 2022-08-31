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
            <button type="submit" class="block">{{ $t('update-task-list') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
            <BaseFormError name="error" /> 
        </form>
    </div>
</template>


<script setup>
import {onMounted, ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
const taskStore = useTaskStore();

const props = defineProps({
    taskList: {
        /** @type {import('../../../../types/task').TaskList} */
        type: Object,
        required: true,
    },
});
const emit = defineEmits(['close']);

onMounted(() => editedTaskList.value = props.taskList ? Object.assign({}, props.taskList) : {});

/** @type {import('../../../../types/task').TaskList} */
const editedTaskList = ref({});

async function updateTaskList() {
    await taskStore.updateTaskList(editedTaskList.value)
    close();
}
function close() {
    editedTaskList.value = {},
    emit('close');
}
</script>
