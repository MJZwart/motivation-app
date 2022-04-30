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
