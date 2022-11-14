<template>
    <form @submit.prevent="createTemplate">
        <SimpleInput
            id="name"
            v-model="template.name"
            type="text"
            name="name"
            :label="$t('task-name')"
            :placeholder="$t('name')"
        />
        <SimpleInput
            id="description"
            v-model="template.description"
            type="text"
            name="description"
            :label="$t('description-optional')"
            :placeholder="$t('description')"
        />
        <div class="form-group">
            <label for="type">{{ $t('type') }}</label>
            <select id="type" v-model="template.type" name="type">
                <option v-for="(type, index) in taskTypes" :key="index" :value="type.value">{{ $t(type.text) }}</option>
            </select>
            <BaseFormError name="type" />
        </div>
        <SimpleInput
            id="difficulty"
            v-model="template.difficulty"
            type="range"
            name="difficulty"
            :label="$t('difficulty') + ': ' + template.difficulty + '/5'"
            min="1"
            max="5"
        />
        <button id="create-new-task-button" type="submit" class="block">{{ $t('create-new-template') }}</button>
        <button type="button" class="block button-cancel" @click="emit('close')">{{ $t('cancel') }}</button>
    </form>
</template>

<script setup lang="ts">
import {TASK_TYPES} from '/js/constants/taskConstants';
import {ref} from 'vue';

const taskTypes = TASK_TYPES;

const template = ref({
    name: '',
    description: '',
    difficulty: 3,
    type: 'GENERIC',
});

const emit = defineEmits(['close', 'submit']);

function createTemplate() {
    emit('submit', template.value);
}
</script>