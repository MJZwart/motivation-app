<template>
    <div v-if="taskList">
        <SimpleInput 
            id="name" 
            v-model="taskList.name"
            type="text" 
            name="name" 
            :label="$t('task-list-name')"
            :placeholder="$t('name')"  />
        <SubmitButton class="block" @click="$emit('submit', taskList)">{{ $t('update-task-list') }}</SubmitButton>
        <button type="button" class="block button-cancel" @click="$emit('close')">{{ $t('cancel') }}</button>
        <BaseFormError name="error" /> 
    </div>
</template>


<script setup lang="ts">
import {ref} from 'vue';
import {deepCopy} from '/js/helpers/copy';
import {TaskList} from 'resources/types/task';

const props = defineProps<{form: TaskList}>();
defineEmits(['close', 'submit']);

const taskList = ref(deepCopy(props.form));
</script>
