<template>
    <div>
        <SimpleInput 
            id="name" 
            v-model="taskList.name"
            type="text" 
            name="name" 
            :label="$t('task-list-name')"
            :placeholder="$t('name')"  />
        <SubmitButton id="create-new-task-list-button" class="block" @click="$emit('submit', taskList)">
            {{ 'id' in form ? $t('edit-task-list') : $t('create-new-task-list') }}
        </SubmitButton>
        <button type="button" class="block button-cancel" @click="$emit('close');">{{ $t('cancel') }}</button>
    </div>
</template>


<script setup lang="ts">
import type {NewTaskList, TaskList} from 'resources/types/task';
import {ref} from 'vue';
import {deepCopy} from '/js/helpers/copy';
defineEmits(['close', 'submit']);

const props = defineProps<{form: TaskList | NewTaskList}>();

const taskList = ref<NewTaskList | TaskList>(deepCopy(props.form));
</script>
