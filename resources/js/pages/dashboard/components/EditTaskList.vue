<template>
    <div v-if="taskList">
        <form @submit.prevent="updateTaskList">
            <SimpleInput 
                id="name" 
                v-model="taskList.name"
                type="text" 
                name="name" 
                :label="$t('task-list-name')"
                :placeholder="$t('name')"  />
            <SubmitButton class="block">{{ $t('update-task-list') }}</SubmitButton>
            <button type="button" class="block button-cancel" @click="$emit('close')">{{ $t('cancel') }}</button>
            <BaseFormError name="error" /> 
        </form>
    </div>
</template>


<script setup lang="ts">
import {ref} from 'vue';
import {deepCopy} from '/js/helpers/copy';

const props = defineProps<{form: any}>();
const emit = defineEmits(['close', 'submit']);

const taskList = ref(deepCopy(props.form));

async function updateTaskList() {
    // await taskStore.updateTaskList(editedTaskList.value)
    emit('submit', taskList.value);
}
</script>
