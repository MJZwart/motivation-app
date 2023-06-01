<template>
    <div>
        <SimpleInput 
            id="name" 
            v-model="taskList.name"
            type="text" 
            name="name" 
            :label="$t('task-list-name')"
            :placeholder="$t('name')"  />
        <FormControls
            :submit-text="'id' in form ? $t('edit-task-list') : $t('create-new-task-list')"
            @submit="$emit('submit', taskList)"
            @cancel="$emit('close')"
        />
    </div>
</template>


<script setup lang="ts">
import type {NewTaskList, TaskList} from 'resources/types/task';
import {ref} from 'vue';
import {deepCopy} from '/js/helpers/copy';
import FormControls from '/js/components/global/FormControls.vue';
defineEmits(['close', 'submit']);

const props = defineProps<{form: TaskList | NewTaskList}>();

const taskList = ref<NewTaskList | TaskList>(deepCopy(props.form));
</script>
