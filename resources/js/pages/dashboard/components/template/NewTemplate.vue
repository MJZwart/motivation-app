<template>
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
    <FormControls
        :submit-text="templateToEdit ? $t('edit-template') : $t('create-new-template')"
        @submit="createTemplate"
        @cancel="$emit('close')"
    />
</template>

<script setup lang="ts">
import {TASK_TYPES} from '/js/constants/taskConstants';
import {onMounted, PropType, ref} from 'vue';
import {NewTemplate, Template} from 'resources/types/task';
import FormControls from '/js/components/global/FormControls.vue';

const props = defineProps({
    templateToEdit: {
        type: Object as PropType<Template>,
        required: false,
    },
});
const emit = defineEmits(['close', 'submit', 'edit']);

onMounted(() => {
    if (props.templateToEdit)
        template.value = props.templateToEdit;
});

const taskTypes = TASK_TYPES;

const template = ref<NewTemplate>({
    name: '',
    description: '',
    difficulty: 3,
    type: 'GENERIC',
});

function createTemplate() {
    if (props.templateToEdit)
        emit('edit', template.value);
    else
        emit('submit', template.value);
}
</script>